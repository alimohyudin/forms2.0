<template>
  <div class="row page">
    <!-- <CommonHeader></CommonHeader> -->

    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="/" class="logo d-flex align-items-center w-auto">
                  <img src="../assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">Crypto Automation</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Email Verification</h5>
                    <p class="text-center small">Please check your <b>Inbox</b> or <b>Spam</b></p>
                  </div>

                  <form class="row g-3 needs-validation" novalidate ref="form" id="verifyEmail_form"
                    v-on:submit.prevent="verifyEmail">

                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="email" class="form-control" id="yourEmail" :value="email"
                          readonly="readonly" aria-readonly="readonly">
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="code" class="form-label">Code</label>
                      <input type="text" name="code" class="form-control" id="code" required>
                      <div class="invalid-feedback">Please enter code from email!</div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Verify</button>
                    </div>
                  </form>
                  <div class="col-12" style="margin-top: 20px;">
                    <p class="small mb-0">Didn't get the code? <a role="button" style="color: blue"
                        @click="resendCode">Resend Code</a></p>                    
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>

    <CommonFooter></CommonFooter>
  </div>
</template>

<script>
import CommonHeader from './CommonHeader.vue'
import CommonFooter from './CommonFooter.vue'

export default {
  name: 'verify-email-page',
  components: {
    CommonHeader,
    CommonFooter
  },
  props: ['email', 'code'],
  data() {
    return {
      codeResent: true,
      secondsLeft: 30
    }
  },
  methods: {
    verifyEmail() {
      let formData = $("#verifyEmail_form").serialize();
      console.log(formData);
      let that = this;
      this.$local
        .postRequest("/user/verify-email", formData, this)
        .then(function (data) {
          console.log(data);
          //that.users = data.users;
          that.$router.push({ name: "user-dashboard-page" });

          return;
        })
        .catch(function (msg) {
          console.log(msg);
          that.$toaster.error(msg);
          return;
        });
    },
    verifyEmailWithLink(email, code){
      let formData = new FormData();

      formData.append("email", email);
      formData.append("code", code);

      let that = this;

      this.$local
        .postRequest("/user/verify-email", formData, this)
        .then(function (data) {
          console.log(data);
          //that.users = data.users;
          that.$router.push({ name: "user-dashboard-page" });

          return;
        })
        .catch(function (msg) {
          console.log(msg);
          that.$toaster.error(msg);
          return;
        });
        
    },
    resendCode() {
      let formData = $("#verifyEmail_form").serialize();
      console.log(formData);
      let that = this;
      this.$local
        .postRequest("/user/resend-email-verification", formData, this)
        .then(function (data) {
          console.log(data);
          //that.users = data.users;
          that.$toaster.success("Email sent again.");
          that.codeResent = true

          return;
        })
        .catch(function (msg) {
          console.log(msg);
          that.$toaster.error(msg);
          return;
        });
    },

  },
  mounted() {
    console.log("mounted")
    console.log(this.email, this.code)
    if (this.email && this.code) {
      this.verifyEmailWithLink(this.email, this.code);
    }
  }
}
</script>