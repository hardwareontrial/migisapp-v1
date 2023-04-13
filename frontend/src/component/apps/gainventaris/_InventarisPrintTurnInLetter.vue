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
              <th class="text-left" style="width: 15%">Kode</th>
              <th class="" style="width: 20%">Nama Barang</th>
              <th class="" style="width: 15%">Merk</th>
              <th class="">Spesifikasi</th>
              <th class="text-center" style="width: 5%">Check</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(data, i) in selectedPrint" :key="i">
              <td style="text-align: center;"> {{ i +1 }} </td>
              <td style="text-align: left; vertical-align: middle"> {{ data.kd_brg}} </td>
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
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2" id="signoff-date">Lawang, {{ $moment().format('DD MMMM YYYY') }}</td>
              </tr>
              <tr>
                <td colspan="2" id="signoff-one">Diterima Oleh,</td>
                <td></td>
                <td></td>
                <td colspan="2" id="signoff-one">Diserahkan Oleh,</td>
              </tr>
              <tr>
                <td colspan="2" style="font-size:10pt;">
                  <u>{{ letterType === 1 ? selectedUserProps.name : (userIsAdmin === 1 ? 'ADMINISTRATOR' : userdata.detailuser.name) }}</u> <br />
                  {{ letterType === 1 ? selectedUserProps.position : (userIsAdmin === 1 ? '' : userdata.detailuser.position.name) }}
                </td>
                <td></td>
                <td></td>
                <td colspan="2" style="font-size:10pt;">
                  <u>{{ letterType === 1 ? (userIsAdmin === 1 ? 'ADMINISTRATOR' : userdata.detailuser.name) : selectedUserProps.name }}</u> <br />
                  {{ letterType === 1 ? (userIsAdmin === 1 ? '' : userdata.detailuser.position.name) : selectedUserProps.position }}
                </td>
              </tr>
              <tr v-if="positionParent.length > 0">
                <td colspan="6" id="signoff-two">Diketahui oleh,</td>
              </tr>
              <tr v-if="(positionParent.length === 2) && userIsAdmin === 0">
                <td></td>
                <td colspan="2" style="font-size:10pt;" id="signoff-three">
                  <u>{{ positionParent[0].name }}</u> <br />
                  {{ positionParent[0].position_name }}
                </td>
                <td colspan="2" style="font-size:10pt;" id="signoff-three">
                  <u>{{ positionParent[1].name }}</u> <br />
                  {{ positionParent[1].position_name }}
                </td>
                <td></td>
              </tr>
              <tr v-else-if="(positionParent.length === 1) && userIsAdmin === 0">
                <td colspan="6" style="font-size:10pt;" id="signoff-three">
                  <u>{{ positionParent[0].name }}</u> <br />
                  {{ positionParent[0].position_name }}
                </td>
              </tr>
<!--              <tr v-else-if="(positionParent.length >= 0)">-->
<!--                <td colspan="6" style="font-size:10pt;" id="signoff-three">-->
<!--                  <u>User</u> <br />-->
<!--                  HRM Manager-->
<!--                </td>-->
<!--              </tr>-->
              <tr v-else-if="positionParent.length === 0">
                <td colspan="6" style="font-size:10pt;" id="signoff-three"></td>
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
    selectedPrint: { type: Array },
    selectedUser: { type: Object },
  },
  components: {
    BRow, BCol,
  },
  data(){
    return{
      authuserid: '',
      authuseradmin: false,
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
      letterType: 1,

      userIsAdmin: 0,
      userIsActive: 1,
      userNik: null,
      positionParent: [],
      selectedUserProps: {
        name: '',
        position: '',
      },
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
                text-align: left;
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
    setLetterType(val){
      this.letterType = val
      setTimeout(() => this.printLetter(), 500)
    },
    getAuthUser(){
      this.userIsAdmin = this.userdata.admin;
      this.userIsActive = this.userdata.active;
      this.userNik = parseInt(this.userdata.nik);

      if(this.userIsAdmin !== 1){
        var userDetail = this.userdata.detailuser;
        if(userDetail && userDetail.position){
          this.extractPositionParent(userDetail.position)
        }else if(!userDetail.position || !userDetail){
          this.$router.push({ name: 'dashboard'})
        }
      }
    },
    extractPositionParent(value){
      let userLevel = value.level;
      if(userLevel > 4){
        let parentObject = value.parent;
        this.extractUserArray(parentObject)
        this.extractPositionParent(parentObject)
      }
    },
    extractUserArray(value){
      let position = value
      let arrayUser = value.user
      if(arrayUser.length > 0){
        for(let user of arrayUser){
          user['position_name'] = position.name
          this.positionParent.push(user)
        }
      }
    },
  },
  watch: {
    'selectedUser': {
      handler(newValue){
        if(newValue){ this.selectedUserProps.name = newValue.name; this.selectedUserProps.position = newValue.position.name }
        else{ this.selectedUserProps.name = ''; this.selectedUserProps.position = '' }
      }
    }
  },
  mounted() {
    this.userdata
    this.getAuthUser()
  },
  computed: {
    userdata(){
      return JSON.parse(localStorage.getItem('userdata'))
    }
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
  text-align: left;
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