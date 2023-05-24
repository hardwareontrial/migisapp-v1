<template>
  <div id="printCert">
    <img :src="propImgCertificate" alt="certificate">
  </div>
</template>

<script>
export default {
  props: {
    propImgCertificate: {
      type: String,
      required: true,
    }
  },
  methods: {
    printCert(){
      const printCert = document.getElementById('printCert').innerHTML;
      const windowPrint = window.open('', '','');
      windowPrint.document.write(`
      <!DOCTYPE html>
      <html>
        <head>
          <title> Print Certificate </title>
          <link rel="icon" href="<%= BASE_URL %>favicon1.ico" />
          <style rel="stylesheet" type="text/css" media="print">
            @page {
              size: A4 landscape;
              margin: 0;
            }
            @media print {
              html, body {
                // height: 210mm;
                // width: 297mm;
                margin:0;
                height:100%;
                -webkit-print-color-adjust:exact !important;
                print-color-adjust:exact !important;
              }
            }
            img {
              width: 100%;
              height: 100%;
              display: block;
              object-fit: cover;
            }
          </style>
        <head>
        <body>
          ${printCert}
        </body>
      </html>
      `);
      windowPrint.document.close();
      setTimeout( ()=> {
        windowPrint.focus();
        windowPrint.print();
        windowPrint.close();
        this.$emit('update: propImgCertificate', '')
      }, 750);
    }
  }
}
</script>

<style lang="scss" scoped>
  img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
  }
</style>
