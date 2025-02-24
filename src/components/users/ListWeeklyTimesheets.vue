<template>
  <div>
    <CommonHeader :userId="userId"></CommonHeader>
    <CommonSidebar :userId="userId"></CommonSidebar>

    <main id="main" class="main dashboard">
      <div class="container">
        <div class="card">
          <div class="card-header"></div>
          <div class="card-header">
            <h3><i class="bi bi-robot"></i> Weekly Timesheets</h3>
          </div>
          <div class="card-body"></div>
          <div class="card-body">
            <div>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <td>Start Date</td>
                    <td>End Date</td>
                    <td>Jobs</td>
                    <td>Employees</td>
                    <td>Op</td>
                  </thead>
                  <tbody>
                    <tr v-for="data in timesheetData">
                      <td>{{ data.timesheet.week_start_date }}</td>
                      <td>{{ data.timesheet.week_end_date }}</td>
                      <td>{{ data.jobs.length }}</td>
                      <td>{{ data.employees }}</td>
                      <td>
                        <router-link :to="'/user/weekly?week_start_date=' + data.timesheet.week_start_date" class="">
                          View
                        </router-link>
                      </td>
                      
                    </tr>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card-header"></div>
        </div>
      </div>
    </main>

    <!--/.Navbar -->
    <div v-if="loading" class="processing" style="">
      <span style="">Processing...</span>
    </div>
    <!-- <CommonFooter></CommonFooter> -->
  </div>
</template>

<script>
  import CommonHeader from '../CommonHeader.vue'
  import CommonSidebar from '../CommonSidebar.vue'

  export default {
    name: 'dashboard-page',
    components: {
      CommonHeader: CommonHeader,
      CommonSidebar: CommonSidebar,
    },
    props: ['userId'],
    data() {
      return {
        timesheetData: [],
        loading: false,
      }
    },
    watch: {
    },
    computed: {
    },
    methods: {
      getDashboardStats() {
        let that = this
        that.loading = true
        that.$local
          .getRequest('/weeklytimesheet')
          .then(function (response) {
            that.timesheetData = response.data
            console.log("Weekly Timesheets: ", response.data)
            that.loading = false
          })
          .catch(function (error) {
            that.loading = false
            console.log(error)
          })
      },
    }, //
    created() {
      let that = this
      that.getDashboardStats()
    },
  }
</script>

<style scoped>
  .box-shadow {
    box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.5);
    padding: 20px;
    margin-bottom: 20px;
  }

  .box-shadow:hover {
    box-shadow: 0px 10px 20px 2px rgba(0, 0, 0, 0.25);

  }

  /* Enter and leave animations can use different */
  /* durations and timing functions.              */
  .slide-fade-enter-active {
    transition: all .3s ease;
  }

  .slide-fade-leave-active {
    transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }

  .slide-fade-enter,
  .slide-fade-leave-to

  /* .slide-fade-leave-active below version 2.1.8 */
    {
    transform: translateX(10px);
    opacity: 0;
  }
</style>
