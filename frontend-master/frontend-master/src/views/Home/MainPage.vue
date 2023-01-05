<template>
  <div class="authorities">
    <v-row no-gutters class="mb-6 space-around">
      <v-col v-if="canReadManufacturerList" class="item" cols="12" sm="6" md="5">
        <img src="/icons/information.svg" class="icon" />
        <div class="containerTitle">
          <span class="title">Сведения о товаропроизводителях</span>
          <div class="containerChildren">
            <span>
              <router-link class="link" to="/manufacturers">Реестр товаропроизводителей</router-link>
            </span>
          </div>
        </div>
      </v-col>
      <v-col v-if="canReadResearchList" class="item" cols="12" sm="6" md="5">
        <img src="/icons/monitoring.svg" class="icon" />
        <div class="containerTitle">
          <span class="title">Госмониторинг</span>
          <div class="containerChildren">
            <span v-if="canReadResearchList">
              <router-link class="link" to="/gosmonitoring/register/submitted-by-manufacturers">
                Реестр поданных сведений
              </router-link>
            </span>
            <span v-if="canReadResearchList">
              <router-link class="link" to="/gosmonitoring/research-register"> Реестр исследований </router-link>
            </span>
          </div>
        </div>
      </v-col>
      <v-col v-if="canReadOrganizationList || canReadRequestList" class="item" cols="12" sm="6" md="5">
        <img src="/icons/informationOrganization.svg" class="icon" />
        <div class="containerTitle">
          <span class="title"
            >Сведения об организациях, осуществляющих в качестве предпринимательской деятельности хранение зерна
          </span>
          <div class="containerChildren">
            <span v-if="canReadOrganizationList">
              <router-link class="link" to="/register-organizations">Реестр организаций</router-link>
            </span>
            <span v-if="canReadRequestList">
              <router-link class="link" to="/requests">Заявления</router-link>
            </span>
            <router-link v-if="canReadTaskList" to="/tasks-for-approval" class="link">Рассмотрение</router-link>
          </div>
        </div>
      </v-col>

      <v-col v-if="$store.getters['auth/isPageAvailable']('/lots/list')" class="item" cols="12" sm="6" md="5">
        <img src="/icons/grainRegister.svg" class="icon" />
        <div class="containerTitle">
          <span class="title">Реестр партий зерна</span>
          <div class="containerChildren">
            <span>
              <router-link to="/lots/list" class="link">Посмотреть реестр</router-link>
            </span>
          </div>
        </div>
      </v-col>

      <v-col v-if="canReadSdizList" class="item" cols="12" sm="6" md="5">
        <img src="/icons/sdis.svg" class="icon" />
        <div class="containerTitle">
          <span class="title">СДИЗ</span>
          <div class="containerChildren">
            <router-link :to="pathSDIZ" class="link">Реестр СДИЗ</router-link>
          </div>
        </div>
      </v-col>
      <v-col v-if="canReadTemplateList" class="item" cols="12" sm="6" md="5">
        <img src="/icons/agree.svg" class="icon" />
        <div class="containerTitle">
          <span class="title">Административные регламенты</span>
          <div class="containerChildren">
            <router-link v-if="canReadTemplateList" to="/approval-templates" class="link"
              >Шаблоны рассмотрения заявлений</router-link
            >
          </div>
        </div>
      </v-col>
      <v-col v-if="canReadDictionaryList" class="item" cols="12" sm="6" md="5">
        <img src="/icons/dictionary.svg" class="icon" />
        <div class="containerTitle">
          <span class="title">Справочники</span>
          <div class="containerChildren">
            <router-link to="/nsi" class="link"> Общие справочники </router-link>
          </div>
        </div>
      </v-col>

      <v-col v-if="canReadLaboratoryList" class="item" cols="12" sm="6" md="5">
        <img src="/icons/laboratory.svg" class="icon" />
        <div class="containerTitle">
          <span class="title">Лаборатории</span>
          <div class="containerChildren">
            <router-link to="/laboratories" class="link"> Реестр лабораторий </router-link>
          </div>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { EAction, mapAccessFlags } from '@/utils';
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component({
  name: 'authorities-main-page',
  computed: {
    ...mapAccessFlags({
      canReadManufacturerList: EAction.READ_MANUFACTURER_REGISTER,
      canReadManufacturerResearchList: EAction.READ_MANUFACTURER_RESEARCH_REGISTER,
      canReadResearchList: EAction.READ_RESEARCH_REGISTER,
      canReadOrganizationList: EAction.READ_ORGANIZATION_REGISTER,
      canReadRequestList: EAction.READ_REQUEST_REGISTER,
      canReadGrainLotList: EAction.READ_GRAIN_LOT_REGISTER,
      canReadSdizList: EAction.READ_SDIZ_REGISTER,
      canReadTemplateList: EAction.READ_APPROVAL_TEMPLATE_REGISTER,
      canReadTaskList: EAction.READ_TASK_REGISTER,
      canReadDictionaryList: EAction.READ_DICTIONARY_REGISTER,
      canReadLaboratoryList: EAction.READ_LABORATORY_REGISTER,
    }),
  },
})
export default class AuthoritiesMainPage extends Vue {
  readonly canReadManufacturerList!: boolean;
  readonly canReadManufacturerResearchList!: boolean;
  readonly canReadResearchList!: boolean;
  readonly canReadOrganizationList!: boolean;
  readonly canReadRequestList!: boolean;
  readonly canReadGrainLotList!: boolean;
  readonly canReadSdizList!: boolean;
  readonly canReadTemplateList!: boolean;
  readonly canReadTaskList!: boolean;
  readonly canReadDictionaryList!: boolean;
  readonly canReadLaboratoryList!: boolean;
  @Prop({ type: String, default: 'АРМ «Государственный мониторинг»' })
  public title!: string;

  get pathSDIZ() {
      return this.$store.getters['auth/isPageAvailable']('/ogv/list-sdiz') ? '/ogv/list-sdiz' : '/sdizs/list';
  }

}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.informationCommodityProducer,
.grainRegister,
.sdis,
.agree {
  height: 140px;
  width: 500px;
  border: 1px solid #e3e3e3;
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

.item {
  height: 140px;
  width: 500px;
  border: 1px solid #e3e3e3;
  border-radius: 8px;
  display: flex;
  flex-direction: row;
  align-items: center;
  margin-top: 20px;

  @include respond-to('medium') {
    margin-left: 15px;
    margin-top: 15px;
    max-width: 440px;
  }

  @include respond-to('small') {
    margin-left: 10px;
    height: 110px;
    max-width: 395px;
  }
}

.grainRegister,
.sdis {
  margin-left: 50px;
}

.containerTitle {
  display: flex;
  flex-direction: column;
  margin-left: 20px;
}

.v-application .title,
.title {
  text-transform: uppercase;
  color: $footer-color !important;
  font-weight: 600 !important;
  font-family: $font-stack !important;
  font-size: 15px !important;
  line-height: 24px;

  @include respond-to('medium') {
    font-size: 12px !important;
    line-height: 18px !important;
  }

  @include respond-to('small') {
    font-size: 11px !important;
  }
}

.containerChildren {
  display: flex;
  flex-direction: column;
  color: $gold-dark-color;
  font-size: 14px;
  margin-top: 5px;
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
  width: 48px;
}

.space-around {
  .item:nth-child(2n) {
    margin-left: 20px;
  }
}

.link,
.link:active,
a:active,
a {
  text-decoration: none;
  color: $gold-dark-color;
  margin-top: 5px;

  &:hover {
    text-decoration: underline;
  }
}
</style>
