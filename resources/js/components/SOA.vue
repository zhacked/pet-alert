<template>
          <v-app>
		<v-container>
			<v-row class="mt-5" v-if="$gate.isAdmin()">
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
							<strong> Statement of Account</strong>
                            <v-spacer></v-spacer>
							<v-card-actions class="card-tools">
                              <input class="form-control" type="file" id="input" accept=".xls,.xlsx"  @change="file" >

                                <button class="btn btn-success" id="button" >
                                   <i class="fa fa-plus"></i>  import excel
                                </button>
							</v-card-actions>
						</v-card-title>


							<v-simple-table class="table-bordered table-hover elevation-1">
								<thead >
									<tr class="text-center ">
										<td>SOA #</td>
										<td>Arena</td>
                                        <td>address</td>
										<td>Email</td>
										<td>Operator</td>
										<td>contact</td>
                                        <td>Action</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>SAO2108062</td>
                                        <td>ARENAN TEST</td>
										<td>TEST ADDRESS</td>
										<td>SAMPLE@GMAIL.COM</td>
										<td>TEST OPERATOR</td>
										<td>0902-434-3434</td>


										<td>
											<button class="btn btn-primary">
												<i class="fa fa-edit"></i> Review
											</button>
										</td>
									</tr>
								</tbody>
							</v-simple-table>


					</v-card>
				</v-col>
			</v-row>

              <pre id="jsondata"></pre>
		</v-container>
	</v-app>
</template>
<script>
     export default {
         data(){
             return {
                 data : [{
                    "name":"jayanth",
                    "data":"scd",
                    "abc":"sdef"
                }],
                selectedFile:[]
             }
         },
        methods: {
            file(event){
                let selectedFile;
                selectedFile =this.file = event.target.files ? event.target.files[0] : null;
   
                var fileReader = new FileReader();
                console.log(fileReader);
    
            }
        }
     }
</script>
