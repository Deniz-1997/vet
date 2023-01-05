import { AddressMapperIn, AddressMapperOutPartial } from '@/services/mappers/address';
import Vue, { CreateElement, VNode } from 'vue';
import Address from '@/components/Address/Address.vue';
import { ESubjectType } from '@/services/enums/subject';
import { IAddressItem, TMapperPlain } from '@/services/models/common';

type TProps<T extends { toJSON: (...args: any[]) => any } = AddressMapperIn> = {
  subjectType: ESubjectType;
  address: TMapperPlain<T>;
};

interface Context extends Vue {
  subjectType: ESubjectType;
  address: TMapperPlain<AddressMapperIn>;
  innerAddress: any;
}

export default Vue.extend({
  name: 'AddressNewComponent',
  model: {
    prop: 'address',
    event: 'save-action',
  },
  props: {
    subjectType: { type: String, required: false },
    address: { type: Object, default: () => ({}) },
  },
  computed: {
    innerAddress: {
      get(this: Context) {
        return new AddressMapperOutPartial(this.address).toJSON();
      },
      set(this: Context, v: IAddressItem) {
        this.$emit('save-action', new AddressMapperIn(v).toJSON());
      },
    },
    props(this: Context): TProps<AddressMapperOutPartial> {
      return {
        subjectType: this.subjectType as ESubjectType,
        address: this.innerAddress,
      };
    },
  },
  methods: {
    onSave(this: Context, v: IAddressItem) {
      this.innerAddress = v;
      this.$emit('close');
    },
  },
  render(h: CreateElement): VNode {
    return h(Address, { props: this.props, on: { saveAction: this.onSave } });
  },
});
