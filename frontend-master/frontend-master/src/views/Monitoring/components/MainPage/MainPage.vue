<template>
  <v-container class="monitoring">
    <v-row>
      <v-col cols="12" md="6" lg="4" xl="4">
        <div class="indicators">
          <img
              src="/icons/indicators.svg"
              class="icon"
          >
          <div class="containerTitle">
            <span class="title">Показатели</span>
            <div class="containerChildren">
              <span>Посмотреть показатели</span>
            </div>
          </div>
        </div>
      </v-col>
      <v-col cols="12" md="6" lg="4" xl="4">
        <div class="results">
          <img
              src="/icons/results.svg"
              class="icon"
          >
          <div class="containerTitle">
            <span class="title">Результаты лабораторных исследований</span>
            <div class="containerChildren">
              <span>Посмотреть результаты</span>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator'

    @Component({
        name: 'monitoring-main-page',
    })

    export default class MonitoringMainPage extends Vue {
        @Prop({type: String, default: 'АРМ Товаропроизводителя'}) public title!: String;

    }
</script>

<style lang="scss" scoped>
    @import '@/assets/styles/_variables.scss';
    @import '@/assets/styles/_mixins.scss';


    .indicators {
        height: 130px;
        width: 500px;
        border: 1px solid #E3E3E3;
        border-radius: 8px;
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-top: 20px;

        @include respond-to('medium') {
            margin-top: 15px;
            width: 440px;
        }

        @include respond-to('small') {
            height: 110px;
            width: 395px;
        }
    }

    .results {
        height: 130px;
        width: 500px;
        border: 1px solid #E3E3E3;
        border-radius: 8px;
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-top: 20px;

        margin-left: 50px;

        @include respond-to('medium') {
            margin-left: 15px;
            margin-top: 15px;
            width: 440px;
        }

        @include respond-to('small') {
            margin-left: 10px;
            height: 110px;
            width: 395px;
        }
    }

    .containerTitle {
        display: flex;
        flex-direction: column;
        margin-left: 20px;
    }

    .title {
        text-transform: uppercase;
        color: $footer-color;
        font-weight: 600;
        font-size: 17px;

        @include respond-to('medium') {
            font-size: 14px;
        }

        @include respond-to('small') {
            font-size: 12px;
        }
    }

    .containerChildren {
        display: flex;
        flex-direction: column;
        color: $gold-dark-color;
        font-size: 16px;

        margin-top: 15px;
        cursor: pointer;

        span {
            margin-top: 5px;

            &:hover {
                text-decoration: underline;
            }
        }

        @include respond-to('medium') {
            font-size: 14px;
        }

        @include respond-to('small') {
            font-size: 12px;
        }
    }

    .icon {
        margin-left: 20px;
    }
</style>