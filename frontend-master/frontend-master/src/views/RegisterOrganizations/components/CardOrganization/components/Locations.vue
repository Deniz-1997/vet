<template>
  <div class="locations">
    <div class="table">
      <div class="tableHeader">
        <span class="spanHeader" v-for="item in headers" :key="item.name">{{ item.label }}</span>
      </div>
      <div class="tableList" >
        <div class="tableListRow" v-for="(item, index) in information.sites" :key="index">
          <span class="spanList">{{ item.site_name }}</span>
          <span class="spanList">{{ item.address ? item.address.address_name : null }}</span>
          <span class="spanList">
            <template v-for="(serv, index) in item.services">
              {{ serv ? serv.service_name + (index !== (item.services.length - 1) ? '; ' : '') : null }}
            </template>
          </span>
          <span class="spanList">{{ item.square }}</span>
          <span class="spanList">{{ item.grain_storage_max }}</span>
          <span class="spanList">{{ item.registration_date }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import { Component, Vue } from 'vue-property-decorator'

  @Component({
    name: 'locations',
    props: { information: Object },
  })

  export default class Locations extends Vue {
    headers = [
      // {
      //   label: 'Действия',
      //   field: 'actions'
      // },
      {
        label: 'Наименование',
        field: 'name',
      },
      {
        label: 'Адрес',
        field: 'address'
      },
      {
        label: 'Услуги',
        field: 'service'
      },
      {
        label: 'Площадь, м2',
        field: 'square'
      },
      {
        label: 'Объем, кг',
        field: 'capacity'
      },
      {
        label: 'Включен в реестр',
        field: 'registration_date'
      },
    ];
  }
</script>

<style lang="scss" scoped>
  @import '@/assets/styles/_variables.scss';
  @import '@/assets/styles/_mixins.scss';

  .locations {
    margin-top: 25px;
  }
 
</style>