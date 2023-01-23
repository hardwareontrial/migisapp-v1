<template>
  <div>
    <b-modal
      id="md-sj-print"
      size="xl"
      title="Print Surat Jalan"
      v-model="show"
      no-close-on-backdrop
      no-close-on-esc
      hide-header-close>
      
      <div id="sj-print">
        <b-row>
          <b-col cols="12">
            <table width="100%" cellspacing="0">
              <tr>
                <td style="width: 14%;">No. Surat Jalan</td>
                <td style="width: 2%;">:</td>
                <td style="width: 48%;">
                  {{ dataprinted.do ? dataprinted.do : dataprinted.dnote }}
                  <span v-if="!dataprinted.do && dataprinted.print_count > 1">-R2</span>
                </td>
                <td>Kepada  :</td>
              </tr>   
              <tr>
                <td style="">Tanggal</td>
                <td style="">:</td>
                <td> {{ $moment(dataprinted.tglkirim).format('DD.MM.Y') }} </td>
                <td> {{ dataprinted.customer }} </td>
              </tr>
              <tr>
                <td style="">No. PO</td>
                <td style="">:</td>
                <td>{{ dataprinted.po }}</td>
                <td rowspan="4" valign="top">{{ dataprinted.alamat }}<br>{{ dataprinted.kota }}</td>
              </tr>
              <tr>
                <td style="">No. Kendaraan</td>
                <td style="">:</td>
                <td>{{ dataprinted.nopol }}</td>
              </tr>
              <tr>
                <td style=""></td>
                <td style=""></td>
                <td></td>
              </tr>
              <tr>
                <td style=""></td>
                <td style=""></td>
                <td></td>
              </tr>
            </table>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <table id="item-tbl" class="table table-bordered" width="100%;" style="margin-top: 30px;">
              <thead>
                <tr>
                  <th style="text-align:center; font-weight:400; width:9%; border:1px solid;">No.</th>
                  <th style="text-align:center; font-weight:400; border:1px solid;">Jenis Barang</th>
                  <th style="text-align:center; font-weight:400; width:24%; border:1px solid;">Quantity</th>
                  <th style="text-align:center; font-weight:400; width:14%; border:1px solid;">Satuan</th>
                </tr>                                        
              </thead>
              <tbody>                                        
                <tr>
                  <td style="text-align:center; border:1px solid;">1</td>
                  <td style="text-align:center; border:1px solid;">{{ dataprinted.item }}</td>
                  <td style="text-align:center; border:1px solid;">{{ dataprinted.qty }}</td>
                  <td style="text-align:center; border:1px solid;">{{ dataprinted.uom }}</td>
                </tr>                                        
              </tbody>
            </table>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="6">
            <table style="margin-top: 15px;">
              <tr>
                <td>Kemasan</td>
                <td>:</td>
                <td></td>
              </tr>
              <tr>
                <td style="width:41%">Tanggal terima Barang</td>
                <td style="width:7%">:</td>
                <td>.........................................................</td>
              </tr>
            </table>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <table id="list_assign_tbl" style="margin-top: 15px;">
              <tr>
                <td rowspan="4" width="40%" horizontal-align="center"> 
                  <p>
                    Barang tsb. bersegel telah diterima diperiksa 
                    dengan betul isi dan kualitasnya
                  </p>
                </td>
              </tr>
              <tr>
                <th width="20%" style="text-align:center; font-weight:400;">Penerima</th>
                <th width="20%" style="text-align:center; font-weight:400;">Pengemudi</th>
                <th width="20%" style="text-align:center; font-weight:400;">Gudang</th>
              </tr>
              <tr height="80px;"></tr>                                    
              <tr>
                <td width="20%" style="text-align:center;">(...........................................)</td>
                <td width="20%" style="text-align:center;">( {{ dataprinted.driver }} )</td>
                <td width="20%" style="text-align:center;">(...........................................)</td>
              </tr>
            </table>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <table width="100%" style="margin-top: 15px;">
              <tr>
                <td rowspan="3" valign="top" style="width: 7%">Note</td>
                <td style="width: 52%;">Brutto..................</td>
              </tr>
              <tr> 
                <td>Tara.....................</td>
                <td>Drum / Box Kosong</td>
              </tr>
              <tr>
                <td>Netto...................</td>                                                                                                                                                                                                                                   
                <td style="font-size: 10pt;"><u>Pengembalian</u> <u>Drum</u> <u>/</u> <u>Box</u> <u>Kosong</u></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td>...........................................</td>
                <td style="font-size: 10pt;" align="right">Drum / box kosong</td>
              </tr>
            </table>   
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <table width="100%" style="margin-top:10px;">
              <tr>
                <td style="text-align: left;"><span style="font-size: 8pt; color:darkgrey;">Tgl Cetak: <i>{{ $moment().format('DD-MMM-Y') }}</i></span></td>
                <td style="text-align: right;">
                  <span style="font-size: 8pt; color:darkgrey;" v-if="dataprinted.do">
                    {{ dataprinted.dnote }}
                  </span>
                  <span style="font-size: 8pt; color:darkgrey;" v-if="dataprinted.printcount > 1 && dataprinted.do">-R2 </span>
                </td>
              </tr>
            </table>   
          </b-col>
        </b-row>
      </div>

      <template #modal-footer>
        <small class="text-danger">*</small>
        <small>Cek kembali sebelum di Cetak.</small>
        <b-button
          type="button"
          variant="primary"
          :disabled="processing"
          @click="submitPrintCount(dataprinted.dnote)">
          {{ processing ? 'Proses...' : 'Print'}}
        </b-button>
        <b-button
          type="button"
          v-if="showBatal"
          variant="outline-danger"
          :disabled="processing"
          @click="closePrint">
          Batal
        </b-button>
      </template>

    </b-modal>
  </div>
</template>

<script>
import {
  BModal,
  BButton,
  BContainer,
  BTableSimple, BThead, BTr, BTh, BTbody, BTd,
  BRow, BCol,
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  components: {
    BModal,
    BButton,
    BContainer,
    BTableSimple, BThead, BTr, BTh, BTbody, BTd,
    BRow, BCol,
  },
  data(){
    return{
      show: false,
      dataprinted: {
        dnote: '',
        do: '',
        po: '',
        tglkirim: '',
        nopol: '',
        customer: '',
        alamat: '',
        kota: '',
        item: '',
        qty: '',
        uom: '',
        driver: '',
        printcount: '',
      },
      processing: false,
      showBatal: false
    }
  },
  methods: {
    openPrint(val, val2){
      this.showBatal = val2
      this.show = true
      this.dataprinted.dnote = val.delivery_no,
      this.dataprinted.do = val.do_no,
      this.dataprinted.po = val.po_no,
      this.dataprinted.tglkirim = val.detail.detail_sending_date,
      this.dataprinted.nopol = val.detail.detail_nopol,
      this.dataprinted.customer = val.detail.detail_customer,
      this.dataprinted.alamat = val.detail.detail_address,
      this.dataprinted.kota = val.detail.detail_city,
      this.dataprinted.item = val.detail.detail_item,
      this.dataprinted.qty = val.detail.detail_qty,
      this.dataprinted.uom = val.detail.detail_uom,
      this.dataprinted.driver = val.detail.detail_driver,
      this.dataprinted.printcount = val.print_count
    },
    closePrint(){
      this.show = false
      this.dataprinted.dnote = '',
      this.dataprinted.do = '',
      this.dataprinted.po = '',
      this.dataprinted.tglkirim = '',
      this.dataprinted.nopol = '',
      this.dataprinted.customer = '',
      this.dataprinted.alamat = '',
      this.dataprinted.kota = '',
      this.dataprinted.item = '',
      this.dataprinted.qty = '',
      this.dataprinted.uom = '',
      this.dataprinted.driver = '',
      this.dataprinted.printcount = ''
    },
    submitPrint(){
      const prtHtml = document.getElementById('sj-print').innerHTML;
      let stylesHtml = '';
      for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
        stylesHtml += node.outerHTML;
      }
      const WinPrint = window.open('', '', '');
      WinPrint.document.write(`<!DOCTYPE html>
      <html>
        <head>
          <style rel="stylesheet" type="text/css" media="print">
            @media print {
              html, body {
                font-size: 10pt;
                font-family: Arial;
                width: 210mm;
                height: 153mm;
                overflow: visible;
                margin-top: 14mm;
                margin-right: 5mm;
              }
              .table {
                border-collapse: collapse !important;
                width: 100%;
              }
              .table tr td{
                font-size: 10pt;
              }
              .table thead tr th {
                background-color: #ffffff;
                // text-transform: uppercase;
                -webkit-print-color-adjust:exact ;
              }
              .table-bordered th,
              .table-bordered td {
                border: 1pt solid black !important;
              }
              .table tr:not(:last-child) td:first-child::first-letter {
                text-transform: uppercase;
              }
            }
          </style>
        </head>
        <body>
          ${prtHtml}
        </body>
      </html>`);
      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();
    },
    submitPrintCount(key){
      this.processing = true
      http
      .post('deliverynote/data/'+key, { deliveryno: key }, {params: {keyword: 'update-print-count'} })
      .then((res) => { 
        this.processing = false
        this.submitPrint()
      })
      .catch((e) => { 
        console.error(e) 
        this.processing = false
      })
      .finally(() => {
        this.closePrint()
        called.$emit('suratjalanlistFetchData')
      })
    },
  },
  mounted(){
    called.$on('suratjalanPrintShow', (val, val2) => { this.openPrint(val, val2) })
  },
}
</script>

<style>

</style>