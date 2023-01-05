<template>
  <div class="ogv">
    <NumbersForManufacturersList
      v-if="$route.path === '/numbers-for-manufacturers' && accessGrantedAuthorities(role)"
    />
    <notifications group="numbers-for-manufacturers" position="bottom right" />
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import NumbersForManufacturersList from '@/views/NumbersForManufacturers/components/NumbersForManufacturersList.vue';
import { PermissionMix } from '@/utils/mixins/permission';
import { EAction } from '@/models/roles';

@Component({
  name: 'NumbersForManufacturers',
  components: { NumbersForManufacturersList, NumbersForManufacturers },
  mixins: [PermissionMix],
})
export default class NumbersForManufacturers extends Vue {
  @Prop({ type: String, default: 'Справочники' }) public title!: string;
  role = EAction.CREATE_LOT_NUMBER;
}
</script>
