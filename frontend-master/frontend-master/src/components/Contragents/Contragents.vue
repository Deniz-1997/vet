<template>
  <div>
    <autocomplete-component
      return-object
      v-model="form"
      :items="contragentsList"
      @searchInputUpdate="searchContragents"
      item-value="short_name"
      item-text="name"
      multiple
      :isActionBlock="true"
    >
      <template #action-item>
        <added-counterparty @showModal="showModal" />
      </template>
    </autocomplete-component>

    <Dialog-component
      v-model="onOpenAddModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="800"
      with-close-icon
      controlsJustify="justify-end"
    >
      <template #title>
        <span>Добавление контрагента </span>
      </template>

      <template #content>
        <add-card
          v-if="onOpenAddModal"
          @close="showModal"
          :autoSelect="true"
          @autoSelect="select"
        />
      </template>
    </Dialog-component>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";
import AutocompleteComponent from "@/components/common/inputs/AutocompleteComponent.vue";
import AddedCounterparty from "@/components/AddedCounterparty/AddedCounterparty.vue";
import AddCard from "./components/AddCard.vue";
import DialogComponent from "@/components/common/Dialog/Dialog.vue";

@Component({
  name: "contragents",
  components: {
    AddedCounterparty,
    AutocompleteComponent,
    DialogComponent,
    AddCard,
  },
})
export default class Contragents extends Vue {
  @Prop({ type: String, default: "Поиск" }) placeholder!: string;

  form = null;
  contragentsList: any[] = [];
  onOpenAddModal = false;

  mounted() {
    this.getListContragents();
  }

  async getListContragents() {
    const data = await this.$store.dispatch('contragents/getList');
    this.contragentsList = data.content;
  }

  showModal() {
    this.onOpenAddModal = !this.onOpenAddModal;
  }

  select(value: any) {
    this.contragentsList.push(value);
    this.form = value;
    this.$emit('save', value);
  }

  async searchContragents(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.contragentsList.findIndex(
      (item: any) => item.name === value
    );
    if (itemIndex === -1) {
      this.getListContragents();
    }
  }
}
</script>

<style lang="scss">
@import "@/assets/styles/_variables.scss";
</style>
