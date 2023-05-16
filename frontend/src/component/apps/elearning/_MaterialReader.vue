<template>
  <div>
    <iframe
      id="iframe"
      :src="file + `${'#toolbar=0'}`"
      width="100%"
      height="600px"
      allowfullscreen
      frameborder="0"
    >
    </iframe>
  </div>
</template>

<script>
export default {
  props: {
    file: {
      type: String,
    },
  },
  mounted() {
    var data = document.getElementById("iframe");
    var dataDoc = data.contentDocument || data.contentWindow.document;
    if (!dataDoc) {
      console.log("Not found in DOM");
    }
    // var iframeContent = dataDoc.getElementById("toolbarViewer");
    // var iframeContent = dataDoc.querySelectorAll('toolbarViewer')
    // console.log(dataDoc);
  },
  watch: {
    file: {
      handler(newValue) {
        console.log(newValue);
      },
    },
  },
};
</script>

<style></style>
