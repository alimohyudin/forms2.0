<template>
  <div id="app">
    <transition
      name="fade"
      mode="out-in"
    >
      <router-view></router-view>
    </transition>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    
  </div>
</template>

<script>
  import "./assets/vendor/bootstrap/css/bootstrap.min.css"
  import "./assets/vendor/bootstrap-icons/bootstrap-icons.css"
  //import "./assets/vendor/boxicons/css/boxicons.min.css"
  //import "./assets/vendor/quill/quill.snow.css"
  //import "./assets/vendor/quill/quill.bubble.css"
  //import "./assets/vendor/remixicon/remixicon.css"
  //import "./assets/vendor/simple-datatables/style.css"
  import "./assets/css/style.css"



  //import 'bootstrap/dist/css/bootstrap.min.css'
  //import 'bootstrap-vue/dist/bootstrap-vue.css'
  /* import './assets/css/custom_style.css'
  import 'jquery/dist/jquery.min.js'*/
  //import 'bootstrap/dist/js/bootstrap.min.js' 
  //import "./assets/vendor/apexcharts/apexcharts.min.js"
  import "./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"
  //import "./assets/vendor/chart.js/chart.umd.js"
  //import "./assets/vendor/echarts/echarts.min.js"
  //import "./assets/vendor/quill/quill.min.js"
  //import "./assets/vendor/simple-datatables/simple-datatables.js"
  //import "./assets/vendor/tinymce/tinymce.min.js"
  //import "./assets/vendor/php-email-form/validate.js"
  //import "./assets/js/main.js"

  export default {
    name: 'rosepos'
  }
</script>

<style>
</style>
