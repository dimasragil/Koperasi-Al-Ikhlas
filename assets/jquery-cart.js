/*

*/

(function ($) {

  "use strict";

  var OptionManager = (function () {
    var objToReturn = {};

    var defaultOptions = {
      checkoutCart: function (products) { },
      getDiscountPrice: function (products) { return null; }
    };

    var getOptions = function (customOptions) {
      var options = $.extend({}, defaultOptions);
      if (typeof customOptions === 'object') {
        $.extend(options, customOptions);
      }
      return options;
    }

    objToReturn.getOptions = getOptions;
    return objToReturn;
  }());


  var ProductManager = (function () {
    var objToReturn = {};

    /*
    PRIVATE
    */
    localStorage.products = localStorage.products ? localStorage.products : "";
    var getIndexOfProduct = function (id) {
      var productIndex = -1;
      var products = getAllProducts();
      $.each(products, function (index, value) {
        if (value.id == id) {
          productIndex = index;
          return;
        }
      });
      return productIndex;
    }
    var setAllProducts = function (products) {
      localStorage.products = JSON.stringify(products);
    }
    var addProduct = function (id, name, price, quantity, image, stock) {
      var products = getAllProducts();
      products.push({
        id: id,
        name: name,
        price: price,
        quantity: quantity,
        image: image,
        stock: stock
      });
      setAllProducts(products);
    }

    /*
    PUBLIC
    */
    var getAllProducts = function () {
      try {
        var products = JSON.parse(localStorage.products);
        return products;
      } catch (e) {
        return [];
      }
    }
    let updateProduct = function (id, quantity, stock) {
      var productIndex = getIndexOfProduct(id);
      if (productIndex < 0) {
        return false;
      }
      var products = getAllProducts();
      var newQuantity = parseInt(products[productIndex].quantity) + parseInt(quantity)
      if (newQuantity > parseInt(stock)) {
        Swal.fire(
          'Jumlah melebihi stok',
          '',
          'error'
        )
      } else {
        products[productIndex].quantity = newQuantity < 1 ? 1 : newQuantity;
        setAllProducts(products);
      }
      return true;
    }
    var setProduct = function (id, name, price, quantity, image, stock) {
      if (typeof id === "undefined") {
        // console.error("id required")
        return false;
      }
      if (typeof name === "undefined") {
        // console.error("name required")
        return false;
      }
      if (typeof image === "undefined") {
        // console.error("image required")
        return false;
      }
      if (!$.isNumeric(price)) {
        // console.error("price is not a number")
        return false;
      }
      if (!$.isNumeric(quantity)) {
        // console.error("quantity is not a number");
        return false;
      }
      if (quantity <= 0) {
        // console.error("quantity must higher than 0");
        return false;
      }

      if (!updateProduct(id, quantity, stock)) {
        addProduct(id, name, price, quantity, image, stock);
      }
      return true
    }
    var clearProduct = function () {
      setAllProducts([]);
    }
    var removeProduct = function (id) {
      var products = getAllProducts();
      products = $.grep(products, function (value, index) {
        return value.id != id;
      });
      setAllProducts(products);
    }
    var getTotalQuantityOfProduct = function () {
      var total = 0;
      var products = getAllProducts();
      $.each(products, function (index, value) {
        total += value.quantity * 1;
      });
      return total;
    }

    objToReturn.getAllProducts = getAllProducts;
    objToReturn.updateProduct = updateProduct;
    objToReturn.setProduct = setProduct;
    objToReturn.clearProduct = clearProduct;
    objToReturn.removeProduct = removeProduct;
    objToReturn.getTotalQuantityOfProduct = getTotalQuantityOfProduct;
    return objToReturn;
  }());

  var checkout = function (options) {
    var products = ProductManager.getAllProducts();
    var invoiceData = $('#invoice').text()
    var totalPrice = parseInt($('#data-total').val())
    var cashPay = parseInt($('#data-cash').val())
    var changeCash = cashPay - totalPrice
    var userId = $('#username').data('id')
    var baseUrl = options.baseUrl + 'sale/checkout'

    var checkoutData = {
      invoice: invoiceData,
      item: JSON.stringify(products),
      total_price: totalPrice,
      cash: cashPay,
      remaining: changeCash,
      user_id: userId
    }
    
    if (!isNaN(cashPay)) {
      $.ajax({
        url: baseUrl,
        type: 'POST',
        data: checkoutData
      })
      .done((data) => {
        // ProductManager.clearProduct()
        Swal.fire({
          icon: 'success',
          title: 'Transaksi berhasil diproses.',
          timer: 2000,
          timerProgressBar: true,
        }).then(() => {
          ProductManager.clearProduct()
          location.reload();
        })
      })
    }
  }

  var drawTable = function (options) {
    var $cartTable = $("#my-cart-table>tbody");
    var products = ProductManager.getAllProducts();
    var total = 0;
    var countChange = (total, cash) => {
      var $change = $('.change-cash')
      var changeData = parseInt(cash) - parseInt(total)
      var changeCurrency = options.currencyFormat(changeData)
      $change.text(changeCurrency)
    }

    $cartTable.empty();
    if (products.length > 0) {
      $.each(products, function (index) {
        total += this.quantity * this.price;
        $cartTable.append(
          `<tr data-id="${this.id}" data-price="${this.price}">
            <td>${index + 1}</td>
            <td>${this.name}</td>
            <td title="Unit Price">${options.currencyFormat(this.price)}</td>
            <td style="display: flex;flex-direction: row;justify-content: space-evenly;" id="${this.id}" stock="${this.stock}">
              <button title="Kurangi jumlah barang" class="btn btn-xs btn-primary update-qty decrease">
                <i class="fa fa-minus"></i>
              </button> 
              <span> ${this.quantity} </span> 
              <button title="Tambah jumlah barang" class="btn btn-xs btn-primary update-qty increase">
                <i class="fa fa-plus"></i>
              </button>
            </td>
            <td title="Total" class="${null}">${options.currencyFormat(this.price * this.quantity)}</td>
            <td title="Hapus produk" class="text-center" style="width: 30px;"><a href="javascript:void(0);" class="btn btn-xs btn-danger delete-btn" data-id="${this.id}"> <i class="fa fa-trash-o"></i> </a></td>
          </tr>`
        );
      });
    } else {
      $cartTable.append(
        `<tr>
            <td colspan="9" class="text-center">Tidak ada item</td>
        </tr>`
      )
    }

    $(".grand-total").text(options.currencyFormat(total));
    $("#data-total").val(total);

    $('.update-qty').click((e) => {
      var id = $(e.currentTarget).parent().attr('id');
      var stock = $(e.currentTarget).parent().attr('stock');
      var hasIncreaseClass = $(e.currentTarget).hasClass('increase');
      var quantity = hasIncreaseClass ? 1 : -1;
      ProductManager.updateProduct(id, quantity, stock);
      drawTable(options);
    })

    $('.delete-btn').click((e) => {
      var id = $(e.currentTarget).data("id");
      ProductManager.removeProduct(id);
      drawTable(options);
    });

    $('#cash')
      .blur((e) => {
        var $cash = $(e.currentTarget)
        var $dataCash = $('#data-cash')
        var $change = $('.change-cash')
        var $checkOutBtn = $('#checkout-btn')
        var alphaRemoved = $cash.val().replace(/[^0-9]/g, '')
        var intCash = parseInt(alphaRemoved, 10)
        var currencyCash = (num) => options.currencyFormat(num)
        var totalData = $('#data-total').val()

        if (!isNaN($cash.val()) && $cash.val() !== '' && intCash >= totalData) {
          $dataCash.val(intCash)
          $cash.val(currencyCash(intCash))
          countChange(totalData, $dataCash.val())
          $checkOutBtn.attr('disabled', false)
        } else if ($cash.val() === '') {
          $cash.val('')
          $dataCash.val('')
          $change.text('Rp. 0')
          $checkOutBtn.attr('disabled', true)
        } else {
          $cash.val(currencyCash($dataCash.val()))
          $checkOutBtn.attr('disabled', false)
        }
      })
      .focus((e) => {
        var cashData = $('#data-cash').val()
        $(e.currentTarget).val(cashData)
      })
  }

  var MyCart = function (target, userOptions) {
    /*
    PRIVATE
    */
    var $target = $(target);
    var $checkOutBtn = $('#checkout-btn');
    var options = OptionManager.getOptions(userOptions);

    /*
    EVENT
    */
    drawTable(options);
    $target.click(function () {
      var targetData = $target[0].dataset
      var id = targetData['id'];
      var name = targetData['name'];
      var price = targetData['price'];
      var quantity = targetData['quantity'];
      var image = targetData['image'];
      var stock = targetData['stock'];

      ProductManager.setProduct(id, name, price, quantity, image, stock);
      options.resetItemInfo()
      drawTable(options);
    });

    $checkOutBtn.click(function () {
      checkout(options)
    })
  }

  $.fn.myCart = function (userOptions) {
    return $.each(this, function () {
      new MyCart(this, userOptions);
    });
  }

})(jQuery);
