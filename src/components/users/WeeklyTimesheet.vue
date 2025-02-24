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
                  <input type="text" name="email" class="form-control" v-model="timesheet.week_start_date" disabled>
                </div>
                <div class="row px-0">
                  <div class="col-5">
                    <label for="startDateInput" class="form-label">Week End Date:&nbsp;</label>
                    <input type="text" name="email" class="form-control" v-model="timesheet.week_end_date" disabled>
                  </div>
                  <div class="col-6">
                    <label for="startDateInput" class="form-label">Foreman: </label>
                    <input type="text" name="email" class="form-control" v-model="timesheet.foreman_name" disabled>
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
                      <template v-for="(job, index) in timesheet.jobs">
                        <tr class="jobname-row" :key="job.job_id">
                          <td>
                            <span>{{ job.job_name }}</span>
                          </td>
                          <td><input type="text" name="email" class="form-control "></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr v-for="(employee, jindex) in job.employees" :key="employee.employee_id + '-' + jindex">
                          <td>
                            <span>{{ employee.employee_name }}</span>
                          </td>
                          <td v-for="(day, dindex) in daysOfWeek" :key="day + '-' + dindex">
                            <input type="text"
                              :class="'form-control ' + ((dindex == 3 || dindex == 4) ? 'saturday' : '')"
                              v-model="employee.hours_worked[day]" min="0" max="24">
                          </td>
                          <td>
                            <span>
                              <input type="text" class="form-control" :value="calculateTotal(employee.hours_worked)"
                                disabled>
                            </span>
                          </td>
                        </tr>
                        <tr class="empty-row">
                          <td>
                            <span></span>
                          </td>
                          <td><input type="text" name="email" class="form-control"></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
              </form>

            </div>
            <div class="row" style="margin: 5px 0px;">
              <div class="col-6">
                <!-- previous week with previousCount -->
                <!-- <button @click="getPreviousWeekTimesheet(previousCount + 1)">&lt; Previous Week</button> -->
                <!-- next week only if previousCount is less than zero -->
                <!-- <button v-if="previousCount > 0" @click="getPreviousWeekTimesheet(previousCount - 1)">Next Week &gt;</button> -->
                
                <router-link v-if="previousCount == 0 || true" :to="'/user/weekly/edit/' + timesheet.week_start_date">
                  <button style="margin-left: 10px;">Edit</button>
                </router-link>
                <router-link :to="'/user/list-weekly'" >
                  <button style="margin-left: 10px;">Dashboard</button>
                </router-link>
              </div>
              <div v-if="previousCount == 0 || true" class="col-6" style="text-align: right;">
                <button @click="save()">Save</button>
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
  import CommonFooter from '../CommonFooter.vue'

  export default {
    name: "landing-page",
    components: {
      CommonFooter
    },
    data() {
      return {
        daysOfWeek: ["wed", "thu", "fri", "sat", "sun", "mon", "tue"],
        timesheet: {},
        previousCount: 0
      };
    },
    computed: {
      
    },
    methods: {
      calculateTotal(hours) {
        let total = 0;
        for (let day in hours) {
          total += parseFloat(hours[day]) || 0;
        }
        if(total === 0) return '';
        return total.toFixed(2).replace(/\.00$/, '');
      },
      save() {
        console.log(this.timesheet);
        let that = this;
        this.$local
          .putRequest("/weeklytimesheet?hours_worked=true", this.timesheet)
          .then(function (data) {
            console.log(data);
            that.$toaster.success("Timesheet saved successfully");
            return;
          })
          .catch(function (msg) {
            console.log(msg);
            that.$toaster.error(msg);
            return;
          });
      },
      getPreviousWeekTimesheet(previousCount) {
        let that = this;
        this.previousCount = previousCount;
        //calculate week start date
        let week_start_date = new Date();
        week_start_date.setDate(week_start_date.getDate() - week_start_date.getDay() + 1 - (previousCount * 7));
        //format to 2025-01-28
        week_start_date = week_start_date.toISOString().split('T')[0];
        console.log(week_start_date);

        this.$local
          .getRequest("/weeklytimesheet?week_start_date=" + week_start_date)
          .then(function (data) {
            console.log(data);
            // round hours_worked to 2 decimal places
            //emp.hours_worked is object with keys as days of week
            data.data.jobs.forEach(job => {
              job.employees.forEach(emp => {
                for (let day in emp.hours_worked) {
                  // console.log("day ", day, emp.hours_worked[day]);

                  if(emp.hours_worked[day] == "") {
                    emp.hours_worked[day] = '';
                    console.log("empty")
                  }
                  else if (emp.hours_worked[day] && parseFloat(emp.hours_worked[day]) >= 0) {
                    // Convert to float and check if it's an integer
                    let hours = parseFloat(emp.hours_worked[day]);
                    emp.hours_worked[day] = Number.isInteger(hours) ? hours.toString() : hours.toFixed(2).replace(/\.00$/, '');
                  }
                }
              });
            });

            that.timesheet = data.data;

            return;
          })
          .catch(function (msg) {
            console.log(msg);
            that.$toaster.error(msg);
          });
      },
    },
    created: function () {
      // localStorage.removeItem('user_token');
      // global.vm.$local.token = '';
      this.getPreviousWeekTimesheet(0);
    }
  };
</script>

<style></style>
