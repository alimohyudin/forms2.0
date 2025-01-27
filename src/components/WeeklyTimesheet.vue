<template>
  <div id="wrapper">
    <!-- <CommonHeader></CommonHeader> -->

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="card ">

            <div class="card-body" style="border: 1px solid black; padding: 0px;">

              <div class="">
                <h5 class="card-title text-center pb-0 fs-4" style="font-weight: bold;">WEEKLY TIMESHEET</h5>
              </div>

              <form class="needs-validation" novalidate ref="form" id="login_form" v-on:submit.prevent="login"
                style="padding: 0px 1px 0px 1px;">

                <div class="col-12 px-0">
                  <label for="startDateInput" class="form-label">Week Start Date: </label>
                  <input type="text" name="email" class="form-control" v-model="startDate">
                </div>
                <div class="row px-0">
                  <div class="col-5">
                    <label for="startDateInput" class="form-label">Week End Date:&nbsp;</label>
                    <input type="text" name="email" class="form-control" v-model="startDate">
                  </div>
                  <div class="col-6">
                    <label for="startDateInput" class="form-label">Foreman: </label>
                    <input type="text" name="email" class="form-control" v-model="startDate">
                  </div>
                </div>


                <div class="col-12 table-responsive-sm mt-3">
                  <table class="table table-sm table-bordered mb-0" style="border: 0px solid black; ">
                    <thead>
                      <tr>
                        <th scope="col"><span>NAME</span></th>
                        <th scope="col"><span>WED</span></th>
                        <th scope="col"><span>THUR</span></th>
                        <th scope="col"><span>FRI</span></th>
                        <th scope="col" class="saturday"><span>SAT</span></th>
                        <th scope="col" class="sunday"><span>SUN</span></th>
                        <th scope="col"><span>MON</span></th>
                        <th scope="col"><span>TUE</span></th>
                        <th scope="col"><span>TOTAL</span></th>

                      </tr>
                    </thead>
                    <tbody>
                      <template v-for="(job, index) in jobs">
                        <tr class="jobname-row">
                          <td>
                            <span>JOB NAME</span>
                          </td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control saturday" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control sunday" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control " v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                        </tr>
                        <tr v-for="(jobname, jindex) in job.names" :key="jobname.id">
                          <td>
                            <span>{{ jobname.name }}</span>
                          </td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control saturday" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control sunday" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                        </tr>
                        <tr class="empty-row">
                          <td>
                            <span></span>
                          </td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control saturday" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control sunday" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                          <td><input type="text" name="email" class="form-control" v-model="startDate"></td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
              </form>

            </div>
            <div class="row" style="margin: 5px 0px;">
              <div class="col-6">
                <button>&lt; Previous</button>
                <button>Next &gt;</button>
                <router-link to="/weekly/edit">
                  <button style="margin-left: 10px;">Edit</button>
                </router-link>
              </div>
              <div class="col-6" style="text-align: right;">
                <button>Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <CommonFooter></CommonFooter>
  </div>
</template>

<script>
  //import CommonHeader from './CommonHeader.vue'
  import CommonFooter from './CommonFooter.vue'

  export default {
    name: "landing-page",
    components: {
      CommonFooter
    },
    data() {
      return {
        jobs: [
          {
            id: 1,
            names: [{
              id: 1,
              name: 'Manuel Arroyo',
            },
            {
              id: 2,
              name: 'Brandon Medina',
            },
            {
              id: 3,
              name: 'Oriando Suarez',
            }]
          }, {
            id: 2,
            names: [{
              id: 1,
              name: 'Juan Arroyo Dominguez',
            },
            {
              id: 2,
              name: 'Juan Arroyo Jr',
            },
            {
              id: 3,
              name: 'Jimmy Rios',
            },
            {
              id: 4,
              name: 'Douglas Sumrall',
            },
            {
              id: 5,
              name: 'Jairo Lazada',
            },
            {
              id: 6,
              name: 'Christian V Lopez',
            }]
          },
          {
            id: 3,
            names: [{
              id: 1,
              name: 'Brian Brewer',
            },
            {
              id: 2,
              name: 'Jose Andrade Gasca',
            },
            {
              id: 3,
              name: 'Bayardo Solorzano Villalto',
            },
            {
              id: 4,
              name: 'Rodrigo Rodriguez Reyna',
            },
            {
              id: 5,
              name: 'Brandon Molden',
            }]
          },
          {
            id: 4,
            names: [{
              id: 1,
              name: 'Jacinto Lopez',
            },
            {
              id: 2,
              name: 'Josue Tomas Lopeza',
            },
            {
              id: 3,
              name: 'Juan Perez',
            },
            {
              id: 4,
              name: 'Yoel Hernandez Gonzalez',
            }]
          }
        ]
      };
    },
    methods: {
      login() {
        let formData = $("#login_form").serialize();
        //console.log(formData);
        let that = this;
        this.$local
          .postRequest("/user/login", formData)
          .then(function (data) {
            //console.log(data);
            //that.users = data.users;
            if (data.accountType === 'admin') {
              that.$router.push({ name: "admin-dashboard-page" });
            } else {
              that.$router.push({ name: "user-dashboard-page" });
            }

            return;
          })
          .catch(function (msg) {
            console.log(msg);
            if (msg && msg.toString().includes('Could not find your account'))
              that.$toaster.error("Email/password is wrong.");
            else if (msg && msg.toString().includes('Verify your email first')) {
              that.$toaster.error("Verify Email first.");
              that.$router.push({ name: "verify-email-page", params: { email: that.email } });
            }
            else
              that.$toaster.error(msg);
            return;
          });
      },
    },
    created: function () {
      localStorage.removeItem('user_token');
      global.vm.$local.token = '';
    }
  };
</script>

<style></style>
