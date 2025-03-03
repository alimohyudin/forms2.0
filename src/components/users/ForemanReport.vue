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
                                                <div><label for="foremanNameInput" class="form-label">Foreman Name:
                                                    </label></div>
                                                <div><label for="projectNameInput" class="form-label">Project Name
                                                    </label></div>

                                            </div>
                                            <div class="col-9 foreman-inputs" style="padding-right: 70px;">
                                                <div class="px-0">
                                                    <input type="text" name="date" class="form-control"
                                                        v-model="reportDate" disabled>
                                                </div>
                                                <div class="">
                                                    <input type="text" name="day" class="form-control"
                                                        v-model="reportDay" disabled>
                                                </div>
                                                <div class="">
                                                    <input type="text" name="foremanName" class="form-control"
                                                        v-model="foremanName">
                                                </div>
                                                <div class="">
                                                    <input type="text" name="projectName" class="form-control"
                                                        v-model="projectName">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="box">
                                                <div style="display: flex; align-items: center;">
                                                    <label for="workPerformedTodayInput" class="form-label bold"
                                                        style="margin-right: 10px;">
                                                        Work Performed Today
                                                    </label>
                                                </div>
                                                <div style="display: flex; align-items: center;"
                                                    v-for="(work, index) in workPerformedToday">
                                                    <input type="text" name="work" class="form-control" style="flex: 1;"
                                                        v-model="work.work">
                                                </div>


                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="box double-column">
                                                <div class="row">
                                                    <div class="border-right col-9 bold">
                                                        Trucks
                                                    </div>
                                                    <div class="col-3 bold">
                                                        Hours
                                                    </div>
                                                </div>
                                                <div v-for="(truck, index) in trucks" :key="index" class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;" @click="truck.editing = true">
                                                        <span v-if="!truck.editing" >{{ truck.name }}</span>
                                                        <input v-else type="text" v-model="truck.name" @blur="finishEditing(truck)" class="form-control" style="width: 100%;">
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="text" name="hours" class="form-control"
                                                            v-model="truck.hours">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="box">
                                                <div style="display: flex; align-items: center;">
                                                    <label for="workPerformedTodayInput" class="form-label bold"
                                                        style="margin-right: 10px;">
                                                        Problems/Delays
                                                    </label>
                                                </div>
                                                <div style="display: flex; align-items: center;"
                                                    v-for="(work, index) in problemsDelays">
                                                    <input type="text" name="work" class="form-control" style="flex: 1;"
                                                        v-model="work.work">
                                                </div>


                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="box">
                                                <div style="display: flex; align-items: center;">
                                                    <label for="workPerformedTodayInput" class="form-label bold"
                                                        style="margin-right: 10px;">
                                                        Equipment Rented
                                                    </label>
                                                </div>
                                                <div style="display: flex; align-items: center;"
                                                    v-for="(work, index) in equipmentRented">
                                                    <input type="text" name="work" class="form-control" style="flex: 1;"
                                                        v-model="work.work">
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <!-- Second column of page -->
                                    <div class="col-5">

                                        <div class="row">
                                            <div class="box weather-box col-9">
                                                <div style="display: flex; align-items: center;">
                                                    <label for="weatherConditionInput" class="form-label"
                                                        style="margin-right: 10px;">
                                                        Weather Condition:
                                                    </label>
                                                    <input type="text" name="weatherCondition" class="form-control"
                                                        v-model="weatherCondition" style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <label for="safetyMeetingInput" class="form-label"
                                                        style="margin-right: 10px;">
                                                        Safety Meeting:
                                                    </label>
                                                    <input type="text" name="safetyMeeting" class="form-control"
                                                        v-model="safetyMeeting" style="flex: 1;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <label for="soilConditionInput" class="form-label"
                                                        style="margin-right: 10px;">
                                                        Soil Condition:
                                                    </label>
                                                    <input type="text" name="soilCondition" class="form-control"
                                                        v-model="soilCondition" style="flex: 1;">
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
                                                <div v-for="(truck, index) in trucks" :key="index" class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        {{ truck.name }}
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="text" name="hours" class="form-control"
                                                            v-model="truck.hours">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="box double-column">
                                                <div class="row">
                                                    <div class="border-right col-9 bold">
                                                        Equipment
                                                    </div>
                                                    <div class="col-3 bold">
                                                        Hours
                                                    </div>
                                                </div>
                                                <div v-for="(equipment, index) in equipmentList" :key="index"
                                                    class="row">
                                                    <div class="border-right col-9" style="line-height: 1.5rem;">
                                                        {{ equipment.name }}
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="text" name="hours" class="form-control"
                                                            v-model="equipment.hours">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </form>

                        </div>
                        <div class="row" style="margin: 5px 0px;">
                            <div class="col-6">
                                <router-link to="/">
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
                reportId: '',
                reportDate: '',
                reportDay: '',
                foremanName: '',
                projectName: '',
                weatherCondition: '',
                safetyMeeting: '',
                soilCondition: '',
                workPerformedToday: [],
                problemsDelays: [],
                equipmentRented: [],
                trucks: [],
                equipmentList: [],
                previousCount: 0
            };
        },
        methods: {
            ensureExtraLine() {
                const ensureExtra = (array, emptyItem) => {
                    if (array.length === 0 || array[array.length - 1].work !== '') {
                        array.push(emptyItem);
                    }
                };

                ensureExtra(this.workPerformedToday, { work: '+' });
                ensureExtra(this.problemsDelays, { work: '+' });
                ensureExtra(this.equipmentRented, { equipment: '+' });
                ensureExtra(this.trucks, { name: '+', hours: '', editing: false });
                ensureExtra(this.equipmentList, { name: '+', hours: '' });
            },
            getPreviousWeekTimesheet(previousCount) {
                let that = this;
                this.previousCount = previousCount;
                //calculate week start date
                let week_start_date = new Date();
                // week_start_date.setDate(week_start_date.getDate() - week_start_date.getDay() + 1 - (previousCount * 7));
                //format to 2025-01-28
                week_start_date = week_start_date.toISOString().split('T')[0];
                console.log(week_start_date);

                this.$local
                    .getRequest("/foreman_reports?week_start_date=" + week_start_date)
                    .then(function (data) {
                        console.log(data);
                        that.reportId = data.data.id;
                        that.reportDate = data.data.report_date;
                        that.reportDay = (new Date(data.data.report_date)).toLocaleDateString('en-US', {
                            weekday: 'long'
                        });
                        that.foremanName = data.data.foreman_name;
                        that.projectName = data.data.project_name;
                        that.weatherCondition = data.data.weather_condition;
                        that.safetyMeeting = data.data.safety_meeting;
                        that.soilCondition = data.data.soil_condition;
                        that.workPerformedToday = JSON.parse(data.data.work_performed_today);
                        that.problemsDelays = JSON.parse(data.data.problems_delays);
                        that.equipmentRented = JSON.parse(data.data.equipment_rented);
                        that.trucks = JSON.parse(data.data.trucks).map(truck => ({ ...truck, editing: false }));
                        that.equipmentList = JSON.parse(data.data.equipment);

                        that.ensureExtraLine();

                        return;
                    })
                    .catch(function (msg) {
                        console.log(msg);
                        that.$toaster.error(msg);
                    });
            },
            save() {
                // this.ensureExtraLine();
                //remove extra lines
                this.workPerformedToday = this.workPerformedToday.filter(work => work.work !== '+');
                this.problemsDelays = this.problemsDelays.filter(work => work.work !== '+');
                this.equipmentRented = this.equipmentRented.filter(work => work.work !== '+');
                this.trucks = this.trucks.filter(truck => truck.name !== '+');
                this.equipmentList = this.equipmentList.filter(equipment => equipment.name !== '+');
                

                let that = this;
                let payload = {
                    report_date: this.reportDate,
                    foreman_name: this.foremanName,
                    project_name: this.projectName,
                    weather_condition: this.weatherCondition,
                    safety_meeting: this.safetyMeeting,
                    soil_condition: this.soilCondition,
                    work_performed_today: JSON.stringify(this.workPerformedToday),
                    problems_delays: JSON.stringify(this.problemsDelays),
                    equipment_rented: JSON.stringify(this.equipmentRented),
                    trucks: JSON.stringify(this.trucks),
                    equipment: JSON.stringify(this.equipmentList)
                };

                console.log(payload);

                this.$local
                    .putRequest("/foreman_reports/"+that.reportId, payload)
                    .then(function (response) {
                        console.log(response);
                        that.$toaster.success("Report saved successfully!");
                    })
                    .catch(function (error) {
                        console.log(error);
                        that.$toaster.error("Failed to save the report.");
                    });
            },
            finishEditing(truck) {
                truck.editing = false;
                if (truck.name === "+") {
                    const plusItemExists = this.trucks.some(t => t.name === "+");
                    if (!plusItemExists) {
                        this.trucks.push({ name: "+", hours: "", editing: false });
                    }
                }
            }
        },
        created: function () {
            this.getPreviousWeekTimesheet(0);
        }
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
