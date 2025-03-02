<template>
    <div>
        <CommonHeader :userId="userId"></CommonHeader>
        <CommonSidebar :userId="userId"></CommonSidebar>

        <main id="main" class="main dashboard">
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="bi bi-people"></i> Manage Users</h3>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="saveUser">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" v-model="userForm.username" :disabled="isEditing"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" v-model="userForm.password"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ isEditing ? 'Update' : 'Add' }}
                                User</button>
                            <button type="button" class="btn btn-secondary" @click="resetForm">Cancel</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user, index in users" :key="user.user_id">
                                        <td>
                                            <h4>{{ user.username }}</h4>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning" @click="editUser(user)">Edit</button>
                                            <button class="btn btn-danger"
                                                @click="deleteUser(user.user_id)">Delete</button>
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
    name: 'users-page',
    components: {
        CommonHeader,
        CommonSidebar,
    },
    props: ['userId'],
    data() {
        return {
            users: [],
            userForm: {
                id: null,
                username: '',
                password: ''
            },
            isEditing: false,
            loading: false,
        }
    },
    methods: {
        fetchUsers() {
            this.loading = true;
            this.$local.getRequest('/users')
                .then(response => {
                    this.users = response.data;
                    this.loading = false;
                })
                .catch(error => {
                    console.error(error);
                    this.loading = false;
                });
        },
        saveUser() {
            let that = this;
            this.loading = true;
            const request = this.isEditing
                ? this.$local.putRequest(`/users/${this.userForm.user_id}`, this.userForm)
                : this.$local.postRequest('/users', this.userForm);

            request.then(response => {
                that.$toaster.success('User saved successfully');
                this.fetchUsers();
                this.resetForm();
                // this.loading = false;
            }).catch(error => {
                console.error(error);
                this.loading = false;
                that.$toaster.error(error.error);
            });
        },
        editUser(user) {
            this.userForm = { ...user, password: '' };
            this.isEditing = true;
        },
        deleteUser(userId) {
            let that = this;
            this.loading = true;
            //ask before deleting
            if (!confirm('Are you sure you want to delete this user?')) {
                this.loading = false;
                return;
            }
            this.$local.deleteRequest(`/users/${userId}`)
                .then(response => {
                    this.fetchUsers();
                    that.$toaster.success('User deleted successfully');
                })
                .catch(error => {
                    console.error(error);
                    that.$toaster.error(error.error);
                    this.loading = false;
                });
        },
        resetForm() {
            this.userForm = {
                id: null,
                username: '',
                password: ''
            };
            this.isEditing = false;
        }
    },
    created() {
        this.fetchUsers();
    }
}
</script>

<style scoped>
/* Add your styles here */
</style>