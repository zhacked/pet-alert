<template>
    <div class="container">
        <v-row class=" justify-content-center">
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box ">
                <div class="inner">
                    <h3>4</h3>

                    <p>Clients</p>
                </div>
                <div class="icon">
                   <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box">
                <div class="inner">
                    <h3>150</h3>

                    <p>Treatments</p>
                </div>
                <div class="icon">
                    <i class="fas fa-notes-medical"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box sidebar-blue text-white">
                <div class="inner">
                    <h3>time</h3>

                    <p>here</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>


        </v-row>
        <v-row>
      
          <div class="col-md-3">
            <div class="sticky-top mb-3">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Employees</h4>
                </div>
                <div class="card-body">
                  <!-- the events -->
                  <div id="external-events">
                    <div class="external-event bg-success">Sharlene</div>
                    <div class="external-event bg-warning">Ann Canimo</div>
                    <div class="external-event bg-info">Janel PEralta</div>
                    <div class="external-event bg-primary">Nathan Belza</div>
                   

                  </div>
                </div>
                <!-- /.card-body -->
              </div>
            </div>
          </div>
          <!-- /.col -->
        </v-row>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }

    
</script>