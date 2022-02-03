<section class="content-header">
    <h1>
        Penjualan
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-user"></i></a></li>
        <li>Transaction</li>
        <li class="active">Sales</li>
    </ol>
</section>

<section class="content">
    <div class="row">

        <!-- --- -->
        <div class="col-lg-8">
            <div class="box box-widget">
                <div class="box-body table-responsive">
                    <div class="col-lg-12">
                        <span id="username" data-id='<?=$userid?>'>Kasir : <?=$name?></span>
                    </div>
                    <table id="my-cart-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th width="15%">Total harga</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="cart_table">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="box box-widget">
                    <div class="box-body">
                        <div align="right">
                            <h4>Invoice <b><span id="invoice"><?= $invoice ?></span></b></h4>
                            <h1><b><span class="grand-total">0</span></b></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="box box-widget">
                    <div id="barcode-box" class="box-body" style="padding-top:20px">
                        <table width="100%">
                            <tr>
                                <td style="vertical-align:top; width:30%">
                                    <label for="barcode">Barcode</label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" id="barcode" class="form-control" autofocus>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top">
                                    <label for="qty">Jumlah</label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" id="qty" value="1" min="1" class="form-control" style="width: 20%;display: inline;margin-right: 10px;" readonly>
                                        <button type="button" id="add_cart" class="btn btn-primary" data-quantity="1" style="margin-right:10px">
                                            <i class="fa fa-cart-plus"></i> Tambah
                                        </button>
                                        sisa stok: <span id="stock-left">0</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span id="stock-alert" style="color:red;display:none;">Jumlah melebihi stok.</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="box box-widget">
                    <div id="information-box" class="box-body" style="height: 200px;padding-top:10px">
                        <div id="item-info-placeholder" style="text-align: center;padding: 35px 0;color: gray;margin-top: 15px;">
                            <i class="fa fa-info-circle" style="font-size: 3em;"></i><br>
                            <span style="font-size: 1.2em;">Informasi produk...</span>
                        </div>
                        <div id="loading-item" style="text-align: center; padding: 35px 0; display:none">
                            <img src="<?= base_url('assets/img/magnify.gif') ?>" alt="" srcset="" style="height: 100px;"><br>
                            <span>Mencari produk...</span>
                        </div>
                        <div id="notfound-item" style="text-align: center;padding: 35px 0;display:none;color: red;margin-top: 15px;">
                            <i class="fa fa-remove" style="font-size: 3em;"></i><br>
                            <span style="font-size: 1.2em;">Produk tidak ditemukan...</span>
                        </div>
                        <div id="item-info" style="text-align: center;display:none;">
                            <img id="item-img" src="" alt="" srcset="" style="height:150px;"><br>
                            <span id="item-name" style="font-size: 1.2em;font-weight:bold;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="box box-widget">
                    <div id="actions-box" class="box-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <button id="process_payment" class="btn btn-lg btn-block btn-success" data-toggle="modal" data-target="#paymentModal">
                                    Bayar
                                </button>
                            </div>
                            <div class="col-lg-6">
                                <button id="cancel_payment" class="btn btn-lg btn-block btn-warning">
                                    Batal
                                </button> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="paymentModalLabel">Invoice <b><span id="invoice"><?= $invoice ?></span></b></h4>
            </div>
            <div class="modal-body">
                <b>Total</b><br>
                <div class="well well-sm"><b><span class="grand-total">0</span></b></div>
                <input type="hidden" id="data-total"> <input type="hidden" id="data-cash">
                <div class="form-group">
                    <label for="cash" class="control-label">Bayar</label>
                    <input type="text" class="form-control" id="cash">
                </div>
                <b>Kembalian</b><br>
                <div class="well well-sm"><b><span class="change-cash">Rp. 0</span></b></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">batal</button>
                <button id="checkout-btn" type="button" class="btn btn-success" disabled>Proses pembelian</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Currency
    let $barcodeSearchBtn = $('#barcode-search-btn')
    let $barcodeField = $('#barcode')
    let $findingItemImg = $('#loading-item')
    let $notFounditemImg = $('#notfound-item')
    let $itemInfoPlaceholder = $('#item-info-placeholder')
    let $itemInfo = $('#item-info')
    let $itemImg = $('#item-img')
    let $itemName = $('#item-name')
    let $addCartBtn = $('#add_cart')
    let $qtyField = $('#qty')
    let $stockLeft = $('#stock-left')

    $qtyField.change(() => {
        $addCartBtn.attr('data-quantity', $qtyField.val())
    })

    let setDataAttr = (data) => {
        if(data){
            let cartAttr = {
                'data-id': data.barcode,
                'data-name': data.name,
                'data-price': data.price,
                'data-image': data.image,
                'data-stock': data.stock
            }
            $addCartBtn.attr(cartAttr)
        } else {
            $addCartBtn.removeAttr('data-id data-name data-price data-image data-stock')
        }
    }

    let resetItemInfo = () => {
        $barcodeField.val('')
        $itemInfoPlaceholder.show()
        $notFounditemImg.hide()
        $itemInfo.hide()
        $findingItemImg.hide()
        $stockLeft.text(0)
        $qtyField.val(1)
        $qtyField.attr('readonly', true)
        setDataAttr(null)
    }

    $addCartBtn.myCart({
        baseUrl: `<?= base_url()?>`,
        currencyFormat: function(price) {
            let curr = currency(price, {
                symbol: 'Rp. ',
                separator: '.',
                precision: '0'
            }).format()
            return curr;
        },
        resetItemInfo: () => resetItemInfo()
    });

    // Barcode check
    $barcodeField.keyup((e) => {
        if ($barcodeField.val() !== '') {
            if (e.which <= 90 && e.which >= 48 || e.which >= 96 && e.which <= 105 || e.key === "Backspace" || e.key === "Delete") {
                $.ajax({
                        url: `<?= base_url('sale/item/') ?>${$barcodeField.val()}`,
                        beforeSend: () => {
                            $itemInfoPlaceholder.hide()
                            $notFounditemImg.hide()
                            $itemInfo.hide()
                            $findingItemImg.show()
                            $stockLeft.text(0)
                            $qtyField.val(1)
                            $qtyField.attr('readonly', true)
                            $(e.currentTarget).removeClass('found')
                        }
                    })
                    .done((data) => {
                        $findingItemImg.hide()
                        let item = JSON.parse(data)
                        if (item.length > 0) {
                            if (item[0].stock > 0) {
                                $itemInfo.show()
                                $itemImg.attr('src', `<?= base_url('uploads/product/') ?>${item[0].image}`)
                                $itemName.text(item[0].name)
                                $stockLeft.text(item[0].stock)
                                $qtyField.attr('readonly', false)
                                setDataAttr(item[0])
                                $(e.currentTarget).addClass('found')
                                return false
                            }
                        }

                        $notFounditemImg.show()
                        $addCartBtn.removeAttr('data-id data-name data-price data-image data-stock')
                    })
            }
        } else {
            resetItemInfo()
        }
        if (e.key === "Enter" && $barcodeField.hasClass('found')) {
            $addCartBtn.trigger('click')
        }
    })

    $('#paymentModal').on('show.bs.modal', (event) => {
        var modal = $(this)

        modal.find('#data-cash').val(0)
        modal.find('#cash').val('Rp. 0')
        modal.find('.change-cash').text('Rp. 0')
    })
</script>