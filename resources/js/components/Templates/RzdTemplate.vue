<template lang="html">
  <container>
    <section>
      <columns class = "is-vcentered">
        <column>
          <img src="/images/logo_rzd.gif" style="padding-top: 20px">
        </column>
        <column class="has-text-right">
          <img src="/images/logo.png" style="padding-top: 20px">
        </column>
      </columns>
    </section>

    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class = "subtitle is-6">Время измерения: {{lastMeasured}}</p>
        </div>
      </div>

      <div class="level-right">
        <div class="level-item">
          <a class="button is-info" @click="history=true">
            <span class="icon is-medium">
              <i class="fas fa-history"></i>
            </span>
          </a>

          <history
            v-if="history"
            @closeHistory="history=false"
          ></history>
        </div>
      </div>
    </nav>

    <hero v-if="data">
      <hero-body>
        <container>

          <div class = "tile is-ancestor">
            <div class = "tile is-vertical is-8">
                <div class = "tile">
                  <div class = "tile is-parent">
                    <air-hum-pres :data = "data" :lastData = "lastData">
                    </air-hum-pres>
                  </div>
                  <div class = "tile is-parent">
                    <precip :data = "data" :lastData = "lastData">
                    </precip>
                  </div>
                </div>
                <div class = "tile">

                  <div class = "tile is-vertical is-6">
                    <div class="tile is-parent">
                      <wind :data = "data" :lastData = "lastData">
                      </wind>
                    </div>
                    <div class="tile is-parent">
                      <telemetry :data = "data" :lastData = "lastData">
                      </telemetry>
                    </div>
                  </div>

                  <div class="tile is-parent">
                    <rail-prism :data = "data" :lastData = "lastData">
                    </rail-prism>
                  </div>

                </div>
            </div>
            <div class="tile is-parent">
              <alert :alerts="alerts" :doorStatus="doorStatus" :powerStatus ="powerStatus">
              </alert>
            </div>

          </div>
        </container>
      </hero-body>
    </hero>
  </container>
</template>

<script>
import AirHumPres from '../DashBlocks/AirHumPres.vue';
import Precip from '../DashBlocks/Precip.vue';
import Wind from '../DashBlocks/Wind.vue';
import Alert from '../DashBlocks/Alert.vue';
import RailPrism from '../DashBlocks/RailPrism.vue';
import Telemetry from '../DashBlocks/Telemetry.vue';

import History from '../RzdComponents/History.vue';


export default {
  name: "rzd-template",

  components: {
    AirHumPres,
    Precip,
    Wind,
    Alert,
    History,
    RailPrism,
    Telemetry,
  },

  props: {
    data: Array,
    alerts: null,
  },

  data() {
    return {
      history:false,
      updatingDoor: null,
      doorStatus: undefined,
      powerStatus: undefined,
    }
  },

  mounted() {
    this.getDoorStatus();
    this.updatingDoor = setInterval(() => {
          this.getDoorStatus();
          this.getPowerStatus();
      }, 5000);
  },

  methods: {
    getDoorStatus() {
      axios.get('/api/door')
      .then((response) => {
        this.doorStatus = Number(response.data.is_opened);
      })
    },


    getPowerStatus() {
      axios.get('/api/power')
      .then((response) => {
        this.powerStatus = response.data.power_status;
      })
    }
  },

  watch: {
    doorStatus: function(newStatus, oldStatus) {
      if (oldStatus !== undefined && newStatus && !oldStatus){
        let alertSound = new Audio('audio/sound.wav');
        alertSound.play();
      }
    },

    powerStatus: function(newStatus, oldStatus) {
      if (oldStatus !== undefined && newStatus && !oldStatus){
        let alertSound = new Audio('audio/sound.wav');
        alertSound.play();
      }
    },
  },

  computed: {
    lastData: function() {
      return (this.data)?this.data[this.data.length - 1]:null;
    },
    lastMeasured: function() {
      return (this.lastData)?moment(this.lastData.measured_at).format('DD MMMM HH:mm'):null;
    }
  },
}
</script>

<style lang="css">
</style>
