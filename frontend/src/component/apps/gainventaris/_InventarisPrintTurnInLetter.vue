<template>
  <div id="inventaris-turn-in">
    <section class="invoice-preview-wrapper">
      <b-row style="margin-bottom: 5em; margin-top: 2em;">
        <b-col cols="12" class="text-center">
          <h1 class="page-header">
            <strong> BERITA ACARA <br> SERAH TERIMA INVENTARIS KANTOR </strong>
          </h1>
        </b-col>
      </b-row>
      <b-row style="margin-bottom: 1em">
        <b-col cols="12" class="text-justify" style="margin-bottom: 0.5em">
          Pada hari ini {{ $moment().format('dddd') }}, {{ $moment().format('DD MMMM YYYY') }}, serah terima barang inventaris sebagai berikut:
        </b-col>
        <b-col cols="12" class="text-justify" style="margin-bottom: 0.5em">
          Ruang: 
        </b-col>
      </b-row>
      <b-row style="margin-bottom: 1em">
        <b-col cols="12" class="text-justify" style="margin-bottom: 0.5em">
          <table class="tbl-data" style="width: 100%;">
            <thead>
              <tr>
                <th class="text-center" style="width: 5%">No.</th>
                <th class="text-center" style="width: 10%">Kode</th>
                <th class="" style="width: 15%">Nama Barang</th>
                <th class="" style="width: 15%">Merk</th>
                <th class="" style="width: 50%">Spesifikasi</th>
                <th class="text-center" style="width: 5%">Check</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(data, i) in SelectedPrint" :key="i">
                <td style="text-align: center;"> {{ i +1 }} </td>
                <td style="text-align: center; vertical-align: middle"> {{ data.kd_brg}} </td>
                <td style="text-align: center; vertical-align: middle"> {{ data.nama_brg }} </td>
                <td style="text-align: center; vertical-align: middle"> {{ data.merkname.name }} </td>
                <td style="text-align: left; vertical-align: middle"> {{ data.spesifikasi }} </td>
                <td style="text-align: center; vertical-align: middle;">
                  <div class="check"></div>
                </td>
              </tr>
            </tbody>
          </table>
        </b-col>
      </b-row>
      <b-row style="margin-bottom: 1em">
        <b-col cols="12" class="text-justify" style="margin-bottom: 0.5em">
          <span class="page-content-two">
          Inventaris kantor tersebut diatas diserahkan dalam keadaan baik dan menjadi tanggung jawab penerima sejak ditanda
          tangani Berita Acara Serah Terima Inventaris Kantor.</span>
        </b-col>
      </b-row>
      <b-row style="margin-bottom: 1em">
        <b-col cols="12" class="text-justify" style="margin-bottom: 0.5em">
          <span class="page-content-two">
          Demikian Berita Acara serah terima dibuat dan dipergunakan sebagai mana mestinya. </span>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" style="margin-bottom: 0.5em">
          <table class="tbl-signoff">
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2" id="signoff-date">Lawang, {{ $moment().format('DD MMMM YYYY') }}</td>
              </tr>
              <tr>
                <td colspan="2" id="signoff-one">Diterima oleh,</td>
                <td></td>
                <td></td>
                <td colspan="2" id="signoff-one">Diserahkan oleh,</td>
              </tr>
              <tr v-if="lettertype === 1">
                <td colspan="2" style="font-size:10pt;"> <u> {{ selectuserdata.name }}</u> <br> {{ selectuserdata.position }} </td>
                <td></td>
                <td></td>
                <td colspan="2" style="font-size:10pt;"> <u> {{ authuser.name }}</u> <br> {{ authuser.position }} </td>
              </tr>
              <tr v-if="lettertype === 2">
                <td colspan="2" style="font-size:10pt;"> <u> {{ authuser.name }}</u> <br> {{ authuser.position }} </td>
                <td></td>
                <td></td>
                <td colspan="2" style="font-size:10pt;"> <u> {{ selectuserdata.name }}</u> <br> {{ selectuserdata.position }} </td>
              </tr>
              <tr>
                <td colspan="6" id="signoff-two">Diketahui oleh,</td>
              </tr>
              <tr v-if="authuser.level === 6">
                <td></td>
                <td colspan="2" id="signoff-three" style="font-size:10pt;"> <u> {{ signmgr.name }} </u> <br> {{ signmgr.position}} </td>
                <td colspan="2" id="signoff-three" style="font-size:10pt;"> <u> {{ signspv.name }} </u> <br> {{ signspv.position }} </td>
                <td></td>
              </tr>
              <tr v-if="authuser.level === 5">
                <td></td>
                <td></td>
                <td colspan="2" id="signoff-three" style="font-size:10pt;"> <u> {{ signmgr.name }} </u> <br> {{ signmgr.position}} </td>
                <td></td>
                <td></td>
              </tr>
              <tr v-if="authuseradmin">
                <td></td>
                <td colspan="2" id="signoff-three" style="font-size:10pt;"> <u> LULUK TRI ASTUTIK </u> <br> HRM & GA MANAGER </td>
                <td colspan="2" id="signoff-three" style="font-size:10pt;"> <u> MEILI WIRAWATI </u> <br> GA SUPERVISOR </td>
                <td></td>
              </tr>
              <tr v-if="authuser.level === 4">
                <td></td>
                <td></td>
                <td colspan="2" id="signoff-three" style="font-size:10pt;"> <u> LULUK TRI ASTUTIK </u> <br> HRM & GA MANAGER </td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </b-col>
      </b-row>
    </section>
  </div>
</template>

<script>
import {
  BRow, BCol
} from 'bootstrap-vue'
import http from '@/customs/axios'

export default {
  props: {
    SelectedPrint: Array,
    UserName: String,
    UserId: Number,
    UserNik: String,
    UserLevel: Number,
    UserPosition: String,
  },
  components: {
    BRow, BCol,
  },
  data(){
    return{
      authuserid: '',
      authuseradmin: false,
      signspv: {
        name: '',
        level: '',
        position: '',
        nik: '',
      },
      signmgr: {
        name: '',
        level: '',
        position: '',
        nik: '',
      },
      authuser: {
        name: '',
        level: '',
        position: '',
        nik: '',
      },
      selectuserdata: {
        name: '',
        level: '',
        position: '',
        nik: '',
      },
      lettertype: 0,
    }
  },
  methods: {
    printLetter(){
      const prtHtml = document.getElementById('inventaris-turn-in').innerHTML;
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
                font-size: 12pt;
                line-height: 1.5em;
                font-family: Arial;
                width: 210mm;
                height: 297mm;
              }
              .tbl-data {
                border-collapse: collapse !important;
                width: 100%;
              }
              .tbl-data tr td{
                font-size: 10pt;
                // text-align: center;
                // vertical-align: middle;
              }
              .tbl-data thead tr th {
                font-size: 10pt;
                background-color: #ddd;
                text-transform: uppercase;
                -webkit-print-color-adjust:exact ;
              }
              .tbl-data th,
              .tbl-data td {
                border: 1pt solid black !important;
              }
              .tbl-data tr:not(:last-child) td:first-child::first-letter {
                text-transform: uppercase;
              }
              .check{
                border-radius: 3px;
                border: 1pt solid black; 
                width: 9px; 
                height: 9px; 
                display: inline-block;
              }
              .page-header{
                text-align: center;
                font-size: 16pt;
                // margin-top: 2em;
              }
              .page-content-two {
                display: flex;
                justify-content: around;
              }
              .tbl-signoff {
                border-collapse: collapse !important;
                border: 0pt solid white !important;
                width: 100%;
              }
              .tbl-signoff td {
                border: 0pt solid white !important;
                width: 16.6%;
              }
              #signoff-date {
                height: 20px; 
                vertical-align: top;
              }
              #signoff-one {
                text-align: left;
                height: 100px; 
                vertical-align: top;
              }
              #signoff-two {
                text-align: center;
                // height: 100px;
                vertical-align: bottom;
              }
              #signoff-three {
                text-align: center;
                height: 120px;
                vertical-align: bottom;
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
    getAuthUser(){
      const data = JSON.parse(localStorage.getItem('userdata'))
      // console.log(data)
      this.authuser = {
        name: data.detailuser.name,
        nik: data.nik,
        position: data.detailuser.position ? data.detailuser.position.name : '' ,
        level: data.detailuser.position ? data.detailuser.position.level : 0,
      }
      this.signuser(data, this.authuser.level)
      this.authuseradmin = !!data.admin
    },
    setLetterType(val){
      this.lettertype = val
      setTimeout(() => this.printLetter(), 500)
    },
    signuser(datauser, level){
      if(level === 6){
        const parent = datauser.detailuser.position.parent
        this.signspv = {
          name: parent.user.length > 0 ? parent.user[0].name : '',
          level: parent.level,
          position: parent.name,
          nik: '',
        }
        const parent2 = parent.parent
        this.signmgr = {
          name: parent2.user.length > 0 ? parent2.user[0].name : '',
          level: parent2.level,
          position: parent2.name,
          nik: '',
        }
      }else if(level === 5){
        const parent = datauser.detailuser.position.parent
        console.log(parent)
        this.signmgr = {
          name: parent.user.length > 0 ? parent.user[0].name : '',
          level: parent.level,
          position: parent.name,
          nik: '',
        }
      }
    }
  },
  watch: {
    'UserId': {
      handler(n){
        if(n === null){
          this.selectuserdata = {
            name: '',
            level: '',
            position: '',
            nik: '',
          }
        }else{
          this.selectuserdata = {
            name: this.UserName,
            level: this.UserLevel,
            position: this.UserPosition,
            nik: this.UserNik,
          }
        }
      }
    }
  },
  created() {
    this.getAuthUser()
  },
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
  .tbl-signoff {
    border-collapse: collapse !important;
    width: 100%;
  }
  .tbl-signoff td {
    // border: 1pt solid black !important;
    width: 16.6%;
  }
  #signoff-date {
    height: 20px; 
    vertical-align: top;
  }
  #signoff-one {
    text-align: left;
    height: 100px; 
    vertical-align: top;
  }
  #signoff-two {
    text-align: center;
    height: 40px;
    vertical-align: bottom;
  }
  #signoff-three {
    text-align: center;
    height: 100px;
    vertical-align: bottom;
  }
</style>