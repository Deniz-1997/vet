<template>
  <div>
    <div>
      <div v-if="title" class="col s12 m8 l8 xl8">
        <div :class="[{ 'new-btn-block': enableNewBtn }, 'inputContainer']">
          <span class="label label--strong">{{ title }}</span>

          <!-- новая кнопка для таблиц -->
          <span
            v-if="(value && value.length < max) && !isShowcase && enableNewBtn"
            class="settingsSpan newBTN"
            @click="handleCreate"
          >
            <img src="/icons/add.svg" class="iconSettings" />
            Добавить
          </span>
        </div>
      </div>

      <span v-if="(value && value.length < max) && !isShowcase && !enableNewBtn" class="settingsSpan" @click="handleCreate">
        <img src="/icons/add.svg" class="iconSettings" />
        Добавить
      </span>
    </div>
    <div ref="table" class="table" :id="idTable">
      <TableHeader v-if="!hideHeader" :options="options" :hide-actions="hideActions" />
      <div class="tableList">
        <slot v-if="!value && !rowOnCreate" name="no-data">
          <text-component class="no-data">
            {{ noDataText }}
          </text-component>
        </slot>
        <div v-for="(item, index) in value" :key="index">
          <EditableRow
            v-if="rowOnEdit && +rowOnEdit === index && isCanEdit"
            v-outer-click="() => handleClickOutside(`${index}`)"
            :value="tempForm"
            :is-showcase="isShowcase"
            :options="options"
            :list="value"
            :hide-actions="!hideActions"
            :rules="rules"
            :messages="messages"
            v-on="baseListeners"
            @validate="onValidate"
            @confirm="updateRow(`${index}`)"
            @reset="() => resetEdit()"
            :id-table="idTable"
          />
          <Row
            v-else
            :value="item"
            :is-showcase="isShowcase"
            :is-show-delete-button="isShowDeleteButton"
            :hide-actions="!hideActions"
            :is-edit-action="isEditAction"
            :options="options"
            :map-before-remove="mapBeforeRemove"
            :is-show-card-button="isShowCardButton"
            :rules="rules"
            :messages="messages"
            @validate="onValidate"
            @edit="editRow(index)"
            @remove="showConfirmModal(item[rowNameField], index)"
          />
        </div>
        <div v-if="rowOnCreate && !isShowcase">
          <EditableRow
            v-outer-click="handleClickOutside"
            :value="tempForm"
            :hide-actions="!hideActions"
            :is-showcase="isShowcase"
            :options="options"
            :list="value"
            :rules="rules"
            :messages="messages"
            v-on="baseListeners"
            @validate="onValidate"
            @confirm="() => updateRow()"
            @reset="() => resetEdit()"
            :id-table="idTable"
          />
        </div>
      </div>
    </div>

    <ConfirmModalDelete
      :show-modal="showModal"
      text="Вы действительно хотите удалить запись"
      :name="removing ? removing.name : ''"
      @close="hideConfirmModal"
      @apply="removeRow"
    />
  </div>
</template>

<script lang="ts">
import memoize from 'lodash/memoize';
import { Component, Prop, Vue } from 'vue-property-decorator';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import TableHeader from './components/TableHeader.vue';
import Row from './components/Row.vue';
import EditableRow from './components/EditableRow.vue';
import 'vue2-datepicker/locale/ru';
import 'vue2-datepicker/index.css';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import TextComponent from '@/components/common/TextComponent.vue';
import Validator from 'validatorjs';

type SelectItem = {
  value: any;
  label: string;
};

type TSelectItemFunction = <T>(list: T[], value: T) => SelectItem[];

type Options = {
  name: string;
  label: string;
  controlType?: 'input' | 'select' | 'date' | 'year';
  mask?: string;
  restrictions?: SelectItem[] | TSelectItemFunction;
  useResctictions?: string;
  style?: any;
  validate?: (...args: any[]) => any;
};

type Props = {
  title: string;
  isShowDeleteButton?: boolean;
  isShowCardButton?: boolean;
  isDeleteRow?: boolean;
  rowNameField: string;
  value: any[];
  options: Options[];
  max?: number;
  isShowcase?: boolean;
  enableNewBtn?: boolean;
  isShow?: boolean;
  editStrategy?: 'inner' | 'outer' | 'semi-inner';
  hideActions?: boolean;
  isEditAction?: boolean;
  mapBeforeRemove?: boolean;
  isCustomCreate?: boolean;
  isNotAddNewField?: boolean;
  isCanEdit?: boolean;
  isCustomEdit?: boolean;
  idTable?: string;
};

const onClickOutside = memoize((el, callback) => {
  return (evt) => {
    const elements = [
      el,
      ...[
        ...document.querySelectorAll('div[role="menu"]'),
        ...document.querySelectorAll('div[role="dialog"]'),
        ...document.querySelectorAll('div[role="listbox"]'),
      ].filter((node) => !node.contains(el)),
    ];

    if (elements.every((element) => evt.target !== element && !element.contains(evt.target))) {
      callback(evt);
    }
  };
});

@Component({
  name: 'EditableTable',
  components: {
    TextComponent,
    ConfirmModalDelete,
    TableHeader,
    Row,
    EditableRow,
    DefaultButton,
  },
  directives: {
    outerClick: {
      bind(el, { value: handler }) {
        window.addEventListener('click', onClickOutside(el, handler), {
          capture: true,
        });
      },
      unbind(el, { value: handler }) {
        window.removeEventListener('click', onClickOutside(el, handler), {
          capture: true,
        });
      },
    },
  },
})
export default class EditableTable extends Vue {
  @Prop({ type: String, default: 'Нет данных' }) readonly noDataText!: string;
  @Prop({ type: Boolean, default: () => false }) readonly hideHeader!: boolean;
  @Prop({ type: Boolean, default: () => false }) readonly isShowCardButton!: Props['isShowCardButton'];
  @Prop({ type: Boolean, default: () => true }) readonly isShowDeleteButton!: Props['isShowDeleteButton'];
  @Prop({ type: Array, default: () => [] }) readonly value!: Props['value'];
  @Prop({ type: Boolean, default: () => false }) readonly mapBeforeRemove!: Props['mapBeforeRemove'];
  @Prop({ type: Boolean, default: () => true }) readonly isEditAction!: Props['isEditAction'];
  @Prop({ type: Array, default: () => [] }) readonly options!: Props['options'];
  @Prop({ type: Number, default: () => [] }) readonly max!: Props['max'];
  @Prop({ type: String, default: '' }) readonly title!: Props['title'];
  @Prop({ type: String, default: '' }) readonly rowNameField!: Props['rowNameField'];
  @Prop({ type: Boolean, default: () => false }) readonly isShowcase!: Props['isShowcase'];
  @Prop({ type: Boolean, default: () => false }) readonly isCanEdit!: Props['isCanEdit'];
  @Prop({ type: Boolean, default: () => false }) readonly enableNewBtn!: Props['enableNewBtn'];
  @Prop({ type: Boolean, default: () => false }) readonly hideActions!: Props['hideActions'];
  @Prop({ type: String, default: 'inner' }) readonly editStrategy!: Props['editStrategy'];
  @Prop({ type: Boolean, default: () => false }) readonly isShow!: Props['isShow'];
  @Prop({ type: Boolean, default: () => false }) readonly isCustomCreate!: Props['isCustomCreate'];
  @Prop({ type: Boolean, default: () => false }) readonly isNotAddNewField!: Props['isNotAddNewField'];
  @Prop({ type: Boolean, default: () => false }) readonly isDeleteRow!: Props['isDeleteRow'];
  @Prop({ type: Object }) readonly rules!: Validator.Rules;
  @Prop({ type: Object }) readonly messages!: Validator.ErrorMessages;
  @Prop({ type: Boolean, default: () => false }) readonly isCustomEdit!: Props['isCustomEdit'];
  @Prop({ type: String, default: '' }) readonly idTable!: Props['idTable'];

  get isValidateField(): boolean {
    return !this.options?.filter(
      (v: Options) => typeof v?.validate === 'function' && !v?.validate(this.tempForm[v.name], this.tempForm)
    ).length;
  }

  rowOnCreate = false;
  rowOnEdit = '';
  tempForm: any = {};
  removing: any = null;
  showModal = false;
  isErrorSave = false;
  isValid = true;

  get baseListeners() {
    return this.options.reduce(
      (result, col) => ({ ...result, [`change.${col.name}`]: (v, form) => this.$emit(`change:${col.name}`, v, form) }),
      {}
    );
  }

  resetEdit() {
    this.rowOnEdit = '';
    this.rowOnCreate = false;
    this.tempForm = {};
    this.isErrorSave = false;
    this.$emit('resetEdit');
  }

  handleClickOutside(index) {
    if (this.isValid && (this.rowOnCreate || this.rowOnEdit === index)) {
      if (!this.tempForm || !Object.keys(this.tempForm).some((key) => this.tempForm[key])) {
        this.resetEdit();
      } else {
        this.updateRow(String(this.rowOnEdit));
      }
    }
  }

  handleCreate() {
    if (this.isCustomCreate) {
      this.$emit('customCreate');
    }
    if (this.editStrategy === 'inner') {
      if (this.isNotAddNewField) {
        return;
      }
      this.rowOnCreate = true;
    } else {
      this.$emit('edit');
    }
  }

  editRow(index?: number) {
    this.rowOnCreate = false;
    if (this.isCustomEdit) {
      this.$emit('customEdit', index);
    } else {
      if (this.editStrategy === 'inner' || this.editStrategy === 'semi-inner') {
        this.rowOnEdit = `${index}`;
        const value = this.value?.find((_, i) => index === i);
        this.$emit('editRowAddition', value);
        this.tempForm = value ? { ...value } : {};
      } else {
        this.$emit('edit', index && +index);
      }
    }
  }

  updateRow(index?: string) {
    if (this.isValidateField) {
      if (!index) {
        this.$emit('input', [...(this.value || []), this.tempForm]);
      } else {
        this.$emit(
          'input',
          (this.value || []).map((item, i) => {
            if (i === +index) {
              return this.tempForm;
            }
            return item;
          })
        );
      }

      this.rowOnCreate = false;
      this.rowOnEdit = '';
      this.tempForm = {};
      this.isErrorSave = false;
    }
  }

  showConfirmModal(name, index) {
    this.showModal = true;
    this.removing = {
      name,
      index,
    };
  }

  hideConfirmModal() {
    this.showModal = false;
    this.removing = null;
  }

  removeRow() {
    if (this.isDeleteRow) {
      this.$emit(
        'deleteItem',
        (this.value || []).filter((_, i) => i === this.removing.index)
      );
    }

    this.$emit(
      'input',
      (this.value || []).filter((_, i) => i !== this.removing.index)
    );

    this.removing = null;
    this.rowOnCreate = false;
    this.rowOnEdit = '';
    this.tempForm = {};
    if (this.mapBeforeRemove) {
      this.$emit('mappingForm');
    }
    this.showModal = false;
  }

  onValidate(evt) {
    this.isValid = evt.isValid;
    this.$emit('validate', evt);
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.label {
  color: $input-border-color;
  font-size: 14px;
  line-height: 16px;
  margin-bottom: 5px;
  display: block;

  &--strong {
    color: $black-color;
    font-weight: 700;
  }
}

.error-block {
  padding: 10px 0;
  color: $error-color;
  text-align: center;
}

.button {
  background-color: $white-color;
  color: $medium-grey-color;
  padding: 15px 35px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 4px;
  margin-left: 15px;
  border: 1px solid $input-border-color;
  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 25px;
  }

  @include respond-to('small') {
    padding: 5px 20px;
  }

  &:hover {
    box-shadow: 0 0 5px rgb(0 0 0 / 50%);
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;
  }
}

.input {
  border: 1px solid $input-border-color;
  border-radius: 3px;
  background: $white-color;
  outline: none;
  height: 40px;
  color: $black-color;
  font-size: 14px;
  line-height: 16px;
  margin: 0;
  padding: 0 10px;
  z-index: 5;
  width: 100%;

  @include respond-to('medium') {
    height: 40px;
    font-size: 16px;
  }

  @include respond-to('small') {
    height: 40px;
    font-size: 14px;
  }

  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    flex: 1 1 150px;
    margin-right: 15px;
    max-width: 150px;
  }

  &--large {
    flex: 1 1 100%;
  }
}

.table {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  margin-bottom: 25px;
  width: 100%;
}

.settingsSpan {
  background: none;
  border: none;
  display: flex;
  align-items: center;
  text-decoration-line: underline;
  font-size: 14px;
  line-height: 16px;
  color: $medium-grey-color !important;
  cursor: pointer;
  text-align: left;
  margin-bottom: 26px;
  width: 100px;

  img {
    display: block;
    width: 16px;
    height: 16px;
    margin-right: 6px;
  }
}

.tableListRow {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-content: center;
  align-items: center;
  padding-top: 5px;
  padding-bottom: 5px;
  min-height: 40px;
  box-sizing: border-box;
  border-bottom: 1px solid $input-border-color;

  .spanList:first-child {
    text-align: left;
    width: 120px;
  }

  .spanList:last-child {
    text-align: right;
    width: calc(100% - 120px);
  }
}

.spanList {
  display: inline-table;
  table-layout: fixed;
  color: $footer-color;
  margin-right: 15px;
  font-size: 0.75rem;

  &:nth-child(2) {
    word-break: break-word;
  }
}

.iconTable {
  width: 20px;
  height: 20px;
  margin-left: 3px;
  cursor: pointer;
}

.tableList {
  width: 100%;
  border-top: 3px solid $medium-grey-color;

  @include respond-to('small') {
    font-size: 12px;
  }
}

.tableInfo {
  display: flex;
  flex-direction: row;
  margin-top: 25px;
}

.settingsSpan.newBTN {
  margin-bottom: 0 !important;
  margin-left: 30px;
}

.new-btn-block {
  overflow: hidden;

  span {
    float: left;
  }
}

.no-data {
  border-bottom: 1px solid $light-grey-color;
  color: $light-grey-color;
  display: block;
  font-size: 12px;
  font-weight: 500;
  line-height: 24px;
  padding: 8px 0;
  text-align: center;
}
</style>
