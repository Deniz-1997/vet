import { Component, Mixins } from 'vue-property-decorator';
import Modal from '@/utils/global/mixins/modal';
import { shallowMount } from '@vue/test-utils';

let component;

beforeEach(() => {
  component = Component({ template: '<div></div>' })(class extends Mixins(Modal) {
    onModalOpen() {
      // do nothing.
    }
  
    onModalClose() {
      // do nothing.
    }
  });
});

describe('modal mixin', () => {
  test('manages computed visibility property', async () => {
    const modal = shallowMount<any>(component, {});

    await modal.setProps({ $isModalOpen: true });
    expect(modal.vm.isModalOpen).toBe(true);

    await modal.setProps({ $isModalOpen: false });
    expect(modal.vm.isModalOpen).toBe(false);

    modal.vm.isModalOpen = false;
    expect(modal.emitted().toggle).toHaveLength(1);

    modal.vm.isModalOpen = true;
    expect(modal.emitted().toggle).toHaveLength(2);
  });

  test('calls handlers on toggle', async () => {
    const modal = shallowMount(component, {});
    const mock = {
      onModalOpen: jest.spyOn(modal.vm as any, 'onModalOpen'),
      onModalClose: jest.spyOn(modal.vm as any, 'onModalClose'),
    };

    await modal.setProps({ $isModalOpen: true });
    expect(mock.onModalOpen).toBeCalled();

    await modal.setProps({ $isModalOpen: false });
    expect(mock.onModalClose).toBeCalled();
  });
});
