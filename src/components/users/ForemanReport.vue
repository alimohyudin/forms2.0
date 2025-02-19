<template>
    <div id="wrapper">
        <!-- <CommonHeader></CommonHeader> -->

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card ">

                        <div class="card-body" style="">

                            <div class="">
                                <h5 class="card-title text-center pb-0 fs-4" style="font-weight: bold;">FOREMAN REPORT
                                </h5>
                            </div>

                            <form class="needs-validation" novalidate ref="form" id="login_form"
                                v-on:submit.prevent="login" style="padding: 0px 1px 0px 1px;">

                                <div class="row">
                                    <!-- First column of page -->
                                    <div class="col-7" style="padding-right: 25px;">
                                        <div class="row">
                                            <div class="col-3 foreman-labels">
                                                <div><label for="startDateInput" class="form-label">Date: </label></div>
                                                <div><label for="startDateInput" class="form-label">Day:&nbsp;</label>
                                                </div>
                                                <div><label for="startDateInput" class="form-label">Foreman Name:
                                                    </label></div>
                                                <div><label for="startDateInput" class="form-label">Project Name
                                                    </label></div>

                                            </div>
                                            <div class="col-9 foreman-inputs" style="padding-right: 70px;">
                                                <div class="  px-0">
                                                    <input type="text" name="email" class="form-control"
                                                        v-model="startDate">
                                                </div>
                                                <div class="">
                                                    <input type="text" name="email" class="form-control"
                                                        v-model="startDate">
                                                </div>
                                                <div class="">
                                                    <input type="text" name="email" class="form-control"
                                                        v-model="startDate">
                                                </div>
                                                <div class="">
                                                    <input type="text" name="email" class="form-control"
                                                        v-model="startDate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="box">
                                                <div style="display: flex; align-items: center;">
                                                    <label for="startDateInput" class="form-label bold"
                                                        style="margin-right: 10px;">
                                                        Work Performed Today
                                                    </label>
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Second column of page -->
                                    <div class="col-5">

                                        <div class="row">
                                            <div class="box weather-box col-9">
                                                <div style="display: flex; align-items: center;">
                                                    <label for="startDateInput" class="form-label"
                                                        style="margin-right: 10px;">
                                                        Weather Condition:
                                                    </label>
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <label for="startDateInput" class="form-label"
                                                        style="margin-right: 10px;">
                                                        Safety Meeting:
                                                    </label>
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <label for="startDateInput" class="form-label"
                                                        style="margin-right: 10px;">
                                                        Spoil Condition:
                                                    </label>
                                                    <input type="text" name="email" class="form-control"
                                                        style="flex: 1;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="box double-column">
                                                <div class="row">
                                                    <div class="border-right col-9 bold">
                                                        Employees
                                                    </div>
                                                    <div class="col-3 bold">
                                                        Hours
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Manuel Arroyo
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Brandon Madina
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Orlando Suarez
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="box double-column">
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Juan Arroyo Dominguez
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Juan Arroyo Jr
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Jimmy Rois
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Douglas Sumrall
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Jairo Lazada
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Christian V Lopez
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="box double-column">
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Brain Brewer
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Jose Andrade Gasca
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Bayardo Solorzano Villalto
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Douglas Sumrall
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Jairo Lazada
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        Christian V Lopez
                                                    </div>

                                                    <div class="col-3">
                                                        <input type="text" name="email" class="form-control"
                                                            style="width: 100%;" v-model="startDate">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                    </div>

                                </div>
                            </form>

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
                //   jobs: [
                //     {
                //       id: 1,
                //       names: [{
                //         id: 1,
                //         name: 'Manuel Arroyo',
                //       },
                //       {
                //         id: 2,
                //         name: 'Brandon Medina',
                //       },
                //       {
                //         id: 3,
                //         name: 'Oriando Suarez',
                //       }]
                //     }, {
                //       id: 2,
                //       names: [{
                //         id: 1,
                //         name: 'Juan Arroyo Dominguez',
                //       },
                //       {
                //         id: 2,
                //         name: 'Juan Arroyo Jr',
                //       },
                //       {
                //         id: 3,
                //         name: 'Jimmy Rios',
                //       },
                //       {
                //         id: 4,
                //         name: 'Douglas Sumrall',
                //       },
                //       {
                //         id: 5,
                //         name: 'Jairo Lazada',
                //       },
                //       {
                //         id: 6,
                //         name: 'Christian V Lopez',
                //       }]
                //     },
                //     {
                //       id: 3,
                //       names: [{
                //         id: 1,
                //         name: 'Brian Brewer',
                //       },
                //       {
                //         id: 2,
                //         name: 'Jose Andrade Gasca',
                //       },
                //       {
                //         id: 3,
                //         name: 'Bayardo Solorzano Villalto',
                //       },
                //       {
                //         id: 4,
                //         name: 'Rodrigo Rodriguez Reyna',
                //       },
                //       {
                //         id: 5,
                //         name: 'Brandon Molden',
                //       }]
                //     },
                //     {
                //       id: 4,
                //       names: [{
                //         id: 1,
                //         name: 'Jacinto Lopez',
                //       },
                //       {
                //         id: 2,
                //         name: 'Josue Tomas Lopeza',
                //       },
                //       {
                //         id: 3,
                //         name: 'Juan Perez',
                //       },
                //       {
                //         id: 4,
                //         name: 'Yoel Hernandez Gonzalez',
                //       }]
                //     }
                //   ]
            };
        },
        methods: {
            // login() {
            //   let formData = $("#login_form").serialize();
            //   //console.log(formData);
            //   let that = this;
            //   this.$local
            //     .postRequest("/user/login", formData)
            //     .then(function (data) {
            //       //console.log(data);
            //       //that.users = data.users;
            //       if (data.accountType === 'admin') {
            //         that.$router.push({ name: "admin-dashboard-page" });
            //       } else {
            //         that.$router.push({ name: "user-dashboard-page" });
            //       }

            //       return;
            //     })
            //     .catch(function (msg) {
            //       console.log(msg);
            //       if (msg && msg.toString().includes('Could not find your account'))
            //         that.$toaster.error("Email/password is wrong.");
            //       else if (msg && msg.toString().includes('Verify your email first')) {
            //         that.$toaster.error("Verify Email first.");
            //         that.$router.push({ name: "verify-email-page", params: { email: that.email } });
            //       }
            //       else
            //         that.$toaster.error(msg);
            //       return;
            //     });
            // },
        },
        //   created: function () {
        //     localStorage.removeItem('user_token');
        //     global.vm.$local.token = '';
        //   }
    };
</script>

<style>
    body {
        font-size: 12px;
    }

    .bold {
        font-weight: bold;
    }

    .foreman-labels {
        padding-top: 10px;
        font-weight: bold;
    }

    .form-label {
        /* font-size: unset; */
    }

    .foreman-labels label {
        margin-top: 1px;
    }

    .form-control {
        display: inline-block;
        width: 6em;
        padding: unset;
        border: unset;
        border-radius: unset;
        border-bottom: 1px solid black;
        color: blue;
        text-align: center;
    }

    tr .form-control {
        border-bottom: unset;
    }

    .foreman-inputs .form-control {
        width: 100%;
    }

    .saturday,
    .sunday {
        width: 1em !important;
    }

    thead tr {
        border-top: 2px solid black !important;
        border-right: 2px solid black !important;
    }


    /* first td */
    td:first-child {
        text-align: left;
        position: relative;
        min-width: 130px;
    }

    td:first-child span {
        position: absolute;
        bottom: 0px;
    }

    .jobname-row {
        /* border-width: 2px !important; */
        /* border-top: 2px solid black !important;
      border-bottom: 2px solid black !important; */
    }

    .jobname-row td:first-child {
        /* font-weight: bold; */

    }


    tr {
        text-align: center;
    }

    tr .form-control {
        width: 3em;
        text-align: center;
    }




    .box {
        border: 3px solid black;
        margin-bottom: 15px;
        padding: unset;
    }

    .box div {
        border-bottom: 2px solid black;
    }

    .box div:last-child {
        border-bottom: unset;
    }

    .box label {
        padding-left: 10px;
        margin-bottom: 0px;
    }

    .box .form-control {
        border-bottom: unset;
        text-align: center;
    }

    .box.double-column {
        padding: 0px 10px;
    }

    .box.double-column .border-right {
        border: unset;
        border-right: 2px solid black;
    }

</style>
