<template>
  <div>
    <CommonHeader :userId="userId"></CommonHeader>
    <CommonSidebar :userId="userId"></CommonSidebar>

    <main id="main" class="main dashboard">
      <div class="container">
        <div class="card">
          <div class="card-header"></div>
          <div class="card-header">
            <h3><i class="bi bi-table"></i> Foreman Reports</h3>
          </div>
          <div class="card-body">
            <router-link :to="'/user/foreman-report'">
              <button class="btn btn-primary">Today</button>
            </router-link>
          </div>
          <div class="card-body">
            <div>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Report Date</th>
                      <th>Foreman Name</th>
                      <th>Project Name</th>
                      <th>Weather Condition</th>
                      <th>Safety Meeting</th>
                      <th>Soil Condition</th>
                      <th>Operations</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="report in foremanReports" :key="report.id">
                      <td>{{ report.report_date }}</td>
                      <td>{{ report.foreman_name }}</td>
                      <td>{{ report.project_name }}</td>
                      <td>{{ report.weather_condition }}</td>
                      <td>{{ report.safety_meeting ? 'Yes' : 'No' }}</td>
                      <td>{{ report.soil_condition }}</td>
                      <td>
                        <router-link :to="'/user/foreman-report?report_date=' + report.report_date" class="">
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

    <div v-if="loading" class="processing">
      <span>Processing...</span>
    </div>
  </div>
</template>

<script>
  import CommonHeader from '../CommonHeader.vue'
  import CommonSidebar from '../CommonSidebar.vue'

  export default {
    name: 'foreman-reports-page',
    components: {
      CommonHeader,
      CommonSidebar,
    },
    props: ['userId'],
    data() {
      return {
        foremanReports: [],
        loading: false,
      }
    },
    methods: {
      getForemanReports() {
        this.loading = true
        this.$local
          .getRequest('/foreman_reports')
          .then(response => {
            this.foremanReports = response.data
            console.log("Foreman Reports: ", response.data)
            this.loading = false
          })
          .catch(error => {
            this.loading = false
            console.log(error)
          })
      },
    },
    created() {
      this.getForemanReports()
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

  .slide-fade-enter-active {
    transition: all .3s ease;
  }

  .slide-fade-leave-active {
    transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }

  .slide-fade-enter,
  .slide-fade-leave-to {
    transform: translateX(10px);
    opacity: 0;
  }
</style>