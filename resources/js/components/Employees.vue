<template>
    <v-app>
        <v-container>
            <v-row class="mt-5">
                <v-col class="col-md-12">
                    <v-card>
                        <v-card-title class="card-header">
                            <strong> Employees</strong>
                            <v-spacer></v-spacer>
                            <v-card-actions class="card-tools">
                                <v-btn
                                    color="success"
                                    elevation="2"
                                    @click="newModal"
                                    >Register New Employee<i
                                        class="fas fa-plus fa-fw"
                                    ></i
                                ></v-btn>
                            </v-card-actions>
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col> </v-col>
                                <v-col>
                                    <v-text-field
                                        v-model="search"
                                        append-icon="mdi-magnify"
                                        label="Search Veterinarian"
                                        single-line
                                        hide-details
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-data-table
                            :headers="headers"
                            :items="employees.data"
                            :items-per-page="10"
                            class="elevation-1 text-center"
                            :search="search"
                        >
                            <template v-slot:[`item.actions`]="{ item }">
                                <v-btn
                                    small
                                    color="primary"
                                    dark
                                    outlined
                                    @click="editModal(item)"
                                >
                                    <i class="fa fa-edit"></i> Update
                                </v-btn>
                                <!-- <v-btn
                                    small
                                    color="red"
                                    dark
                                    outlined
                                    @click="deleteEmployees(item.id)"
                                >
                                    <i class="fa fa-trash"></i> Delete
                                </v-btn> -->
                            </template>
                        </v-data-table>
                    </v-card>
                </v-col>
            </v-row>

            <!-- Modal client  -->
            <div
                class="modal fade"
                id="addNew"
                tabindex="-1"
                role="dialog"
                aria-labelledby="addNewLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header sidebar-blue text-white">
                            <h5
                                class="modal-title"
                                v-show="!editmode"
                                id="addNewLabel"
                            >
                                Add Employees
                            </h5>
                            <h5
                                class="modal-title"
                                v-show="editmode"
                                id="addNewLabel"
                            >
                                Update User's Info
                            </h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <v-form
                            @submit.prevent="
                                editmode ? updateEmployees() : createEmployees()
                            "
                            v-model="valid"
                        >
                            <div class="modal-body">
                                <div class="form-group">
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        name="name"
                                        placeholder="Name"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('name'),
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="name"
                                    ></has-error>
                                </div>
                                <div class="form-group">
                                    <input
                                        v-model="form.address"
                                        type="text"
                                        name="address"
                                        placeholder="Address"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('address'),
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="address"
                                    ></has-error>
                                </div>
                                <div class="form-group">
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        name="email"
                                        placeholder="Email Address"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('email'),
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="email"
                                    ></has-error>
                                </div>

                                <!-- <div class="form-group"> -->
                                    <v-text-field
                                        v-model="form.number"
                                        name="contact"
                                        id="contact"
                                        counter="11"
                                        placeholder="number"
                                        outlined
                                        dense
                                    
                                    ></v-text-field>
                                    <!-- <has-error
                                        :form="form"
                                        field="number"
                                    ></has-error>
                                </div> -->

                                <!-- <div class="form-group">
								<input v-model="form.color" name="color" id="color" type="color"
								placeholder="color"
								class="form-control" :class="{ 'is-invalid': form.errors.has('color') }">
								<has-error :form="form" field="color"></has-error>
							</div>
                         -->
                                <v-container>
                                    <v-subheader>Set Schedule</v-subheader>
                                    <v-row align="center">
                                        <v-col class="d-flex" cols="12" sm="6">
                                            <v-select
                                                v-model="scheduleFrom"
                                                :items="timeAvail()"
                                                
                                                label="From"
                                                dense
                                            ></v-select>
                                        </v-col>

                                        <v-col class="d-flex" cols="12" sm="6">
                                            <v-select
                                                v-model="scheduleTo"
                                                :items="timeAvail()"
                                                
                                                label="To"
                                                dense
                                            ></v-select>
                                        </v-col>

                                    </v-row>
                                </v-container>
                                <div class="form-group">
                                    <select
                                        name="type"
                                        v-model="form.gender"
                                        id="type"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('gender'),
                                        }"
                                    >
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <has-error
                                        :form="form"
                                        field="gender"
                                    ></has-error>
                                </div>

                                <div class="form-group">
                                    <v-color-picker
                                        v-model="form.color"
                                        dot-size="11"
                                        hide-canvas
                                        hide-mode-switch
                                        mode="hexa"
                                        show-swatches
                                        swatches-max-height="80"
                                        width="100%"
                                    ></v-color-picker>
                                </div>
                                <p>
                                    <v-icon class="text-danger"
                                        >mdi-alert-decagram</v-icon
                                    ><span class="text-danger">Noted : </span
                                    ><span
                                        >The Default password is
                                        <span class="text-success"
                                            >petalert123</span
                                        >
                                    </span>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <v-btn
                                    type="button"
                                    color="error"
                                    elevation="2"
                                    data-dismiss="modal"
                                    >Close</v-btn
                                >
                                <v-btn
                                    v-show="editmode"
                                    type="submit"
                                    color="primary"
                                    elevation="2"
                                    >Update</v-btn
                                >
                                <v-btn
                                    :disabled="(scheduleFrom && scheduleTo) ? false : true"
                                    v-show="!editmode"
                                    type="submit"
                                    color="success"
                                    elevation="2"
                                    >SUBMIT</v-btn
                                >
                            </div>
                        </v-form>
                    </div>
                </div>
            </div>
        </v-container>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            headers: [
                { text: "Name", value: "name" },
                { text: "Gender", value: "gender" },
                { text: "Position", value: "position" },
                { text: "Address", value: "address" },
                { text: "Email", value: "email" },
                { text: "Contact Info", value: "number" },
                { text: "", value: "actions", sortable: false },
            ],
             rules: {
          required: value => !!value || 'Required.',
        
        },
         valid: true,
            editmode: false,
            client: {},
            pet: {},
            employees: [],
            length: "",
            search: "",
            form: new Form({
                id: "",
                name: "",
                address: "",
                email: "",
                number: "",
                gender: "",
                position: "veterinarian",
                schedFrom: "",
                schedTo: "",
                color: Math.floor(Math.random() * 16777215).toString(16),
                password: "petalert123",
                type: "employee",
            }),
            hours: [],
            scheduleFrom: null,
            scheduleTo: null,
        };
    },
    methods: {
        loadClient() {
            axios.get("api/client").then(({ data }) => (this.client = data));
        },
        updateEmployees() {
            this.$Progress.start();
            console.log("Editing data");
            const from = this.$moment(this.scheduleFrom,'LT').format('k')
            const to = this.$moment(this.scheduleTo, 'LT').format('k')

            this.form.schedFrom = from;
            this.form.schedTo = to;

            this.form
                .put("api/employeess/" + this.form.id)
                .then(() => {
                    $("#addNew").modal("hide");
                    swal.fire(
                        "Updated!",
                        "Information has been updated.",
                        "success"
                    );
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        editModal(user) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(user);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $("#addNew").modal("show");
        },
        deleteEmployees(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, archive it",
            }).then((result) => {
                if (result.value) {
                    this.form
                        .delete("api/employeess/" + id)
                        .then(() => {
                            swal.fire(
                                "Archived!",
                                "Your file has been Archived!.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
                        })
                        .catch(() => {
                            swal.fire(
                                "Failed!",
                                "There was something wrong.",
                                "warning"
                            );
                        });
                }
            });
        },
        loadEmployees() {
            axios.get("api/employeess").then((data) => (this.employees = data));
        },
        createEmployees() {
            this.$Progress.start();

            const from = this.$moment(this.scheduleFrom,'LT').format('k')
            const to = this.$moment(this.scheduleTo, 'LT').format('k')

            this.form.schedFrom = from.toString();
            this.form.schedTo = to.toString();
            
            this.form
                .post("api/employeess")
                .then(() => {
                    this.scheduleFrom = null;
                    this.scheduleTo = null;
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");
                    toast({
                        type: "success",
                        title: "User Created in successfully",
                    });
                    this.$Progress.finish();
                   
                })
                .catch(() => {});


                console.log(this.scheduleFrom)


        },
        timeAvail(start = 0, end = 23) {
            const locale = "en"; // or whatever you want...
            const hours = [];

            this.$moment.locale(locale); // optional - can remove if you are only dealing with one locale

            for (let hour = start; hour <= end; hour++) {
                hours.push(this.$moment({ hour }).format("h:mm A"));
            }
            return hours;
        },

        timeAvailability(){
            this.hours = tshi.timeAvail();
    
        }


    },
    created() {
        this.loadClient();
        //  this.timeAvailability()
        this.loadEmployees();
        Fire.$on("AfterCreate", () => {
            this.loadEmployees();
        });
    },
};
</script>
