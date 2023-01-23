<template>
  <div id="inventaris-print-qr">
    <div class="row" style="display: inline-block;" v-for="(c, i) in filteredQrCode" :key="i">
      <div class="group">
        <div class="box">
          <div class="title">
            <span>PT MOLINDO INTI GAS</span>
          </div>
          <div class="qr">
            <img :src="c.qrcode" alt="img">
          </div>
          <div class="code">
            <span>{{ c.kd_brg }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { BImg } from 'bootstrap-vue'

export default {
  props: {
    SelectedPrint: Array,
  },
  components: {
    BImg,
  },
  computed: {
    filteredQrCode(){
      const res = this.SelectedPrint.map((item) => {
        return {
          kd_brg: item.kd_brg,
          qrcode: item.qrlink,
        }
      })
      return res
    }
  },
  methods: {
    printqr(){
      const prtHtml = document.getElementById('inventaris-print-qr').innerHTML;
      const WinPrint = window.open('', '', '');
      WinPrint.document.write(`<!DOCTYPE html>
      <html>
        <head>
          <title>Print Qr</title>
          <style rel="stylesheet" type="text/css" media="print">
            @media print {
              html, body {
                width: 210mm;
                height: 297mm;
                font-family: Arial;
              }
              .group {
                margin-right: 2mm;
                margin-bottom: 2mm;
              }
              .group .box {
                width: 22mm !important;
                height: 30mm !important;
                border: 1pt solid black !important;
                padding: 1.5mm;
              }
              .group .title span {
                // border: 1pt solid red !important;
                width: 22mm !important;
                font-family: Arial;
                font-size: 7px;
                font-weight: 600;
                display: flex;
                justify-content:center;
                align-content: center;
                color: black;
              }
              .group .qr img {
                // border: solid 1px red;
                width: 22mm !important;
                height: 22mm !important;
                margin-top: 5px;
              }
              .box .code span {
                // border: solid 1px red;
                width: 22mm !important;
                font-family: Arial;
                font-size: 10px;
                font-weight: 600;
                display: flex;
                justify-content:center;
                align-content: center;
                color: black;
              }
            }
          </style>
        </head>
        <body>
          ${prtHtml}
        </body>
      </html>`);
      WinPrint.document.close();
      setTimeout( ()=> {
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
      }, 750);
    }
  }
}
</script>

<style>

</style>