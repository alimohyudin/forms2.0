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
                <div class="foreman-section" style="padding: 0px 1px 0px 1px;">
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
                      <input type="text" name="email" class="form-control" v-model="timesheet.foreman_name">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Loop through each job -->
              <div v-for="(job, index) in timesheet.jobs" :key="job.job_id" class="job-card">
                <div class="job-header">
                  <h2 @click="job.isEditing = true;" v-if="!job.isEditing">{{ job.job_name }}</h2>
                  <input v-model="job.job_name" v-if="job.isEditing" />
                  <button @click="job.isEditing = false" v-if="job.isEditing">Finish Editing</button>
                  <button @click="deleteJob(index)" class="delete-btn">Delete Job</button>
                </div>

                <!-- Job Employees List -->
                <div class="employee-list">
                  <h3>Employees</h3>
                  <ul>
                    <li v-for="(employee, employeeIndex) in job.employees" :key="employee.employee_id"
                      class="employee-item">
                      <span @click="employee.isEditing = true;" v-if="!employee.isEditing">{{ employee.employee_name
                        }}</span>
                      <input v-model="employee.employee_name" v-if="employee.isEditing" />
                      <button @click="employee.isEditing = false" v-if="employee.isEditing">Finish</button>
                      <button @click="deleteEmployee(index, employeeIndex)" class="delete-btn">Delete</button>
                    </li>
                  </ul>
                  <div class="add-employee">
                    <select v-model="selectedEmployeeId" class="input-field">
                      <option v-for="employee in allEmployees" :key="employee.employee_id" :value="employee.employee_id">
                        {{ employee.employee_name }}
                      </option>
                    </select>
                    <button @click="addEmployee(index)" class="add-btn">Add</button>
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
                <router-link to="/user/weekly/">
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
  import CommonFooter from '../CommonFooter.vue'

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
        selectedEmployeeId: null,
        allEmployees: []
      };
    },
    methods: {
      // Get Job Name by Job ID
      getJobName(jobId) {
        const job = this.timesheet.jobs.find((j) => j.job_id == jobId);
        if (!job) return 'Job Not Found';
        return `${job.job_name}`;
      },
      editJobName(index) {
        console.log('editJobName', index);
        this.timesheet.jobs[index].isEditing = true;
      },
      // Add a new Job
      addJob() {
        const newJob = {
          job_id: Date.now(), // Unique ID for the new job
          job_name: this.newJobName,
          employees: [],
          isEditing: false
        };
        this.timesheet.jobs.push(newJob);
        this.newJobName = ''; // Clear the input field
      },

      // Delete a Job
      deleteJob(index) {
        this.timesheet.jobs.splice(index, 1);
      },

      // Add Employee to Job
      addEmployee(jobIndex) {
        const selectedEmployee = this.allEmployees.find(emp => emp.employee_id === this.selectedEmployeeId);
        if (selectedEmployee) {
          const newEmployee = { ...selectedEmployee, isEditing: false };
          this.timesheet.jobs[jobIndex].employees.push(newEmployee);
        }
      },

      // Delete Employee from Job
      deleteEmployee(jobIndex, employeeIndex) {
        this.timesheet.jobs[jobIndex].employees.splice(employeeIndex, 1);
      },

      save() {
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

      fetchAllEmployees() {
        let that = this;
        this.$local.getRequest('/employees').then(function (data) {
          that.allEmployees = data.data;
        }).catch(function (msg) {
          console.log(msg);
        });
      }
    },
    created: function () {
      let that = this;
      this.$local.getRequest('/weeklytimesheet?week_start_date=' + this.week_start_date).then(function (data) {
        data.data.jobs = data.data.jobs.map(job => ({
          ...job,
          isEditing: false,
          employees: job.employees.map(emp => ({
            ...emp,
            isEditing: false,
          })),
        }));
        that.timesheet = data.data;
      }).catch(function (msg) {
        console.log(msg);
      });

      // Fetch all employees when the component is created
      this.fetchAllEmployees();
    }
  };
</script>

<style></style>
