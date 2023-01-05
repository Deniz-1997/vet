<template>
  <div class="register">
    <v-row v-if="form.registers && form.registers.laboratory">
      <v-col cols="12">
        <InputComponent
          v-if="mode.update && !type.subject && !type.registry && !type.lab && form.headSubject"
          :value="form.headSubject.name"
          name="headSubject"
          placeholder="Выберите головную организацию"
          label="Головная организация"
          disabled
        />
        <SubjectAutocomplete
          v-else
          v-model="form.headSubject"
          placeholder="Выберите головную организацию"
          label="Головная организация"
          is-return-object
          name="headSubject"
        />
      </v-col>
    </v-row>
    <v-row v-if="form.registers && form.registers.manufacturer">
      <v-col cols="12">
        <div class="elementsInput checkbox-block">
          <label class="checkbox">
            <input
              id="auto_in"
              v-model="form.isProcessor"
              type="checkbox"
              name="auto_in"
              :disabled="mode.update && !type.subject && !type.registry && !type.manufacturer"
            />
            <span class="checkbox__icon">
              <img src="/icons/checkbox.svg" />
            </span>
          </label>
          <span class="label">
            Организация, осуществляющая первичную и (или) последующую (промышленную) переработку зерна
          </span>
        </div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <div class="elementsInput checkbox-block">
          <label class="checkbox">
            <input
              id="manufacturer"
              v-model="form.registers.manufacturer"
              :checked="form.registers.manufacturer"
              type="checkbox"
              name="manufacturer"
              :disabled="(mode.update && !type.subject && !type.registry) || form.involved_registries?.manufacturer"
            />
            <span class="checkbox__icon">
              <img src="/icons/checkbox.svg" />
            </span>
          </label>
          <span class="label"> Реестр товаропроизводителей </span>
        </div>
      </v-col>
      <v-col cols="12">
        <div class="elementsInput checkbox-block">
          <label class="checkbox">
            <input
              id="laboratory"
              v-model="form.registers.laboratory"
              :checked="form.registers.laboratory"
              type="checkbox"
              name="laboratory"
              :disabled="(mode.update && !type.subject && !type.registry) || form.involved_registries?.laboratory"
            />
            <span class="checkbox__icon">
              <img src="/icons/checkbox.svg" />
            </span>
          </label>
          <span class="label"> Реестр лабораторий </span>
        </div>
      </v-col>
      <v-col cols="12">
        <div class="elementsInput checkbox-block">
          <label class="checkbox">
            <input
              id="ogv"
              v-model="form.registers.ogv"
              :checked="form.registers.ogv"
              type="checkbox"
              name="ogv"
              :disabled="(mode.update && !type.subject && !type.registry) || form.involved_registries?.ogv"
            />
            <span class="checkbox__icon">
              <img src="/icons/checkbox.svg" />
            </span>
          </label>
          <span class="label"> Реестр органов государственной власти (ОГВ) </span>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import HeadSubject from '@/views/Laboratories/components/HeadSubject.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SubjectAutocomplete from '@/components/Subject/SubjectAutocomplete.vue';
import Form from '@/utils/global/mixins/form';

@Component({
  name: 'card-register',
  components: { InputComponent, HeadSubject, SubjectAutocomplete },
})
export default class CardRegister extends Mixins(Form) {
  @Prop({ type: Boolean, default: false }) readonly isLoading?: boolean;

  get type() {
    const { path } = this.$route;

    const result: Record<string, boolean> = {
      manufacturer: path.includes('/manufacturers'),
      lab: path.includes('/laboratories'),
      ogv: path.includes('/stateAuthority'),
      registry: path.includes('/subjects'),
    };

    if (!result.manufacturer && !result.lab && !result.ogv && !result.registry) {
      result.subject = path.includes('/stateAuthority');
    }

    return result;
  }

  get mode() {
    return {
      create: this.$route.path.includes('/create'),
      update: this.$route.path.includes('/edit'),
    };
  }
}
</script>
