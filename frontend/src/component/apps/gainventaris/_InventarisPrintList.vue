<template>
  <div id="inventaris-print-list">
    <div style="margin-bottom: 10px;" v-if="LocationName">
      <feather-icon icon="MapPinIcon"/>
      <strong style="margin-left: 5px;"> {{ LocationName }} </strong>
    </div>
    <div style="margin-bottom: 10px;" v-if="UserName">
      <feather-icon icon="UserIcon"/>
      <strong style="margin-left: 5px;"> {{ UserName }} </strong>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
        <th class="text-center" width="10%">Kode</th>
        <th class="text-center" width="">Nama Barang</th>
        <th class="text-center" width="10%">Status</th>
        <th class="text-center" width="10%">Aktif</th>
        <th class="text-center" width="25%" v-if="LocationName">User</th>
        <th class="text-center" width="25%" v-if="UserName">Lokasi</th>
        <th class="text-center" width="8%">Check</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="data in SelectedPrint" :key="data.id">
          <td>{{ data.kd_brg }}</td>
          <td>{{ data.nama_brg }}</td>
          <td>{{ statusopt[0][data.status_id] }}</td>
          <td>{{ activeopt[0][data.active] }}</td>
          <td v-if="LocationName">
            <span>{{ data.user1_id ? data.user1name.name : '' }}</span>
            <span v-if="data.user2_id"><br>{{ data.user2name.name }}</span>
          </td>
          <td v-if="UserName"> {{ data.locname.name }} </td>
          <td>
            <div style="" class="check">
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import http from '@/customs/axios'

export default {
  props: {
    LocationName: String,
    UserName: String,
    SelectedPrint: Array,
  },
  data(){
    return{
      statusopt: [
        { 1: 'Stock', 2: 'User', 3: 'Properti', 4: 'Perawatan' },
      ],
      activeopt: [
        { 0: 'Tidak Aktif', 1: 'Aktif' },
      ],
    }
  },
  methods: {
    printList(){
      // alert('printList')
      const prtHtml = document.getElementById('inventaris-print-list').innerHTML;
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
                height: 297mm;
              }
              .table {
                border-collapse: collapse !important;
                width: 100%;
              }
              .table tr td{
                font-size: 10pt;
                text-align: center;
                vertical-align: middle;
              }
              .table thead tr th {
                background-color: #ddd;
                text-transform: uppercase;
                -webkit-print-color-adjust:exact ;
              }
              .table-bordered th,
              .table-bordered td {
                border: 1pt solid black !important;
              }
              .table tr:not(:last-child) td:first-child::first-letter {
                text-transform: uppercase;
              }
              .check{
                border-radius: 3px;
                border: 1pt solid black; 
                width: 9px; 
                height: 9px; 
                display: inline-block;
              }
              .tbl-notes {
                border-collapse: collapse !important;
                width: 100%;
                margin-top: 5px;
              }
              .tbl-notes-border th, td, tr{
                border: 1pt solid black !important;
              }
              #test {
                height: 80px;
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
  },
  created(){
    called.$on('_inventarisPrintListprintList', () => { this.printList() })
  }
}
</script>

<style lang="scss" scoped>
  .check {
    border-radius: 3px;
    border: 1px solid black; 
    width: 12px; 
    height: 12px; 
    display: inline-block;
  }
</style>