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
              <div v-for="(job, index) in jobs" :key="job.id" class="job-card">
                <div class="job-header">
                  <h2>{{ getJobName(job.id) }}</h2>
                  <button @click="deleteJob(index)" class="delete-btn">Delete Job</button>
                </div>

                <!-- Job Employees List -->
                <div class="employee-list">
                  <h3>Employees</h3>
                  <ul>
                    <li v-for="(employee, employeeIndex) in job.names" :key="employee.id" class="employee-item">
                      {{ employee.name }}
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
      // Get Job Name by Job ID
      getJobName(jobId) {
        const job = this.jobs.find(j => j.id === jobId);
        return `Job ${jobId}`;
      },

      // Add a new Job
      addJob() {
        const newJob = {
          id: this.jobs.length + 1,
          names: []
        };
        this.jobs.push(newJob);
      },

      // Delete a Job
      deleteJob(index) {
        this.jobs.splice(index, 1);
      },

      // Add Employee to Job
      addEmployee(jobIndex) {
        const newEmployeeId = this.jobs[jobIndex].names.length + 1;
        const newEmployee = { id: newEmployeeId, name: 'New Employee' };
        this.jobs[jobIndex].names.push(newEmployee);
      },

      // Delete Employee from Job
      deleteEmployee(jobIndex, employeeIndex) {
        this.jobs[jobIndex].names.splice(employeeIndex, 1);
      },

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
