<template>
    <div>
        <CommonHeader :userId="userId"></CommonHeader>
        <CommonSidebar :userId="userId"></CommonSidebar>

        <main id="main" class="main dashboard">
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="bi bi-person"></i> Manage Employees</h3>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="saveEmployee">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" v-model="employeeForm.employee_name"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ isEditing ? 'Update' : 'Add' }}
                                Employee</button>
                            <button type="button" class="btn btn-secondary" @click="resetForm">Cancel</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="employee in employees" :key="employee.employee_id">
                                        <td>
                                            <h4>{{ employee.employee_name }}</h4>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning"
                                                @click="editEmployee(employee)">Edit</button>
                                            <button class="btn btn-danger"
                                                @click="deleteEmployee(employee.employee_id)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    name: 'employees-page',
    components: {
        CommonHeader,
        CommonSidebar,
    },
    props: ['userId'],
    data() {
        return {
            employees: [],
            employeeForm: {
                employee_id: null,
                employee_name: ''
            },
            isEditing: false,
            loading: false,
        }
    },
    methods: {
        fetchEmployees() {
            this.loading = true;
            this.$local.getRequest('/employees')
                .then(response => {
                    this.employees = response.data;
                    this.loading = false;
                })
                .catch(error => {
                    console.error(error);
                    this.loading = false;
                });
        },
        saveEmployee() {
            this.loading = true;
            const request = this.isEditing
                ? this.$local.putRequest(`/employees/${this.employeeForm.employee_id}`, this.employeeForm)
                : this.$local.postRequest('/employees', this.employeeForm);

            request.then(response => {
                this.fetchEmployees();
                this.resetForm();
                // this.loading = false;
                this.$toaster.success('Employee saved successfully');
            }).catch(error => {
                console.error(error);
                this.$toaster.error(error.error);
                this.loading = false;
            });
        },
        editEmployee(employee) {
            this.employeeForm = { ...employee };
            this.isEditing = true;
        },
        deleteEmployee(employeeId) {
            this.loading = true;
            this.$local.deleteRequest(`/employees/${employeeId}`)
                .then(response => {
                    this.fetchEmployees();
                    this.$toaster.success('Employee deleted successfully');
                    // this.loading = false;
                })
                .catch(error => {
                    console.error(error);
                    this.$toaster.error(error.error);
                    this.loading = false;
                });
        },
        resetForm() {
            this.employeeForm = {
                id: null,
                name: ''
            };
            this.isEditing = false;
        }
    },
    created() {
        this.fetchEmployees();
    }
}
</script>

<style scoped>
/* Add your styles here */
</style>