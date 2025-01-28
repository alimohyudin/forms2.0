<template>
  <div id="wrapper">
    <!-- <CommonHeader></CommonHeader> -->

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="card ">

            <div class="card-body" style="border: 1px solid black; padding: 0px;">

              <div class="">
                <h5 class="card-title text-center pb-0 fs-4" style="font-weight: bold;">EDIT WEEKLY TIMESHEET</h5>
              </div>

              <!-- Loop through each job -->
              <!-- Loop through each job -->
              <div v-for="(job, index) in timesheet.jobs" :key="job.job_id" class="job-card">
                <div class="job-header">
                  <h2>{{ getJobName(job.job_id) }}</h2>
                  <button @click="deleteJob(index)" class="delete-btn">Delete Job</button>
                </div>

                <!-- Job Employees List -->
                <div class="employee-list">
                  <h3>Employees</h3>
                  <ul>
                    <li v-for="(employee, employeeIndex) in job.employees" :key="employee.employee_id" class="employee-item">
                      {{ employee.employee_name }}
                      <button @click="deleteEmployee(index, employeeIndex)" class="delete-btn">Delete Employee</button>
                    </li>
                  </ul>
                  <div class="add-employee">
                    <input v-model="newEmployeeName" placeholder="Employee Name" class="input-field" />
                    <button @click="addEmployee(index)" class="add-btn">Add Employee</button>
                  </div>
                </div>
              </div>

              <!-- Add Job Section -->
              <div class="add-job">
                <input v-model="newJobName" placeholder="Job Name" class="input-field" />
                <button @click="addJob" class="add-btn">Add Job</button>
              </div>

            </div>
            <div class="row" style="margin: 5px 0px;">
              <div class="col-6">
                <router-link to="/weekly/">
                  <button>Weekly Timesheet</button>
                </router-link>
              </div>
              <div class="col-6" style="text-align: right;" @click="save">
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
    props: ['week_start_date'],
    data() {
      return {
        timesheet: {},
        newJobName: '',
        newEmployeeName: ''

      };
    },
    methods: {
      // Get Job Name by Job ID
      getJobName(jobId) {
        const job = this.timesheet.jobs.find((j)=> j.job_id == jobId);
        if (!job) return 'Job Not Found';
        return `${job.job_name}`;
      },

      // Add a new Job
      addJob() {
        const newJob = {
          // id: this.jobs.length + 1,
          names: []
        };
        this.timesheet.jobs.push(newJob);
      },

      // Delete a Job
      deleteJob(index) {
        this.timesheet.jobs.splice(index, 1);
      },

      // Add Employee to Job
      addEmployee(jobIndex) {
        console.log('addEmployee', jobIndex);
        // const newEmployeeId = this.jobs[jobIndex].names.length + 1;
        const newEmployee = { employee_name: this.newEmployeeName };
        this.timesheet.jobs[jobIndex].employees.push(newEmployee);
      },

      // Delete Employee from Job
      deleteEmployee(jobIndex, employeeIndex) {
        this.timesheet.jobs[jobIndex].employees.splice(employeeIndex, 1);
      },

      save() {
        //console.log(formData);
        let that = this;
        this.$local
          .putRequest("/weeklytimesheet", this.timesheet)
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
    },
    created: function () {
      let that = this;
      // console.log('created');
      // console.log("week_start_date: ", this.week_start_date);
      localStorage.removeItem('user_token');
      global.vm.$local.token = '';
      //get request to http://localhost/forms2.0/backend-php/api/weeklytimesheet
      this.$local.getRequest('/weeklytimesheet?week_start_date='+this.week_start_date).then(function(data){
        console.log("timesheetData", data.data);
        that.timesheet = data.data;

      }).catch(function(msg){
        console.log(msg);
      });
    }
  };
</script>

<style></style>
