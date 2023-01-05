import FileTable from '@/views/Files/components/FileTable.vue';
import { mount, shallowMount } from '@vue/test-utils';

interface IFileTable extends Vue {
  pageable: {
    pageSize: number;
    pageNumber: number;
  };
  list: any[];
  safe(path: string): string;
}

const stubs = ['v-row', 'v-col', 'v-overlay', 'v-progress-circular'];

const items = [
  {
    name: 'test1',
    path: 'test1.ext',
    date: '11.12.2012',
  },
  {
    name: 'test2',
    path: 'test2.ext',
    date: '12.12.2012',
  },
  {
    name: 'test3',
    path: 'test3.ext',
    date: '13.12.2012',
  },
  {
    name: 'test4',
    path: 'test4.ext',
    date: '14.12.2012',
  },
  {
    name: 'test5',
    path: 'test5.ext',
    date: '15.12.2012',
  },
  {
    name: 'test6',
    path: 'test6.ext',
    date: '16.12.2012',
  },
];

describe('FileTable.vue', () => {
  test('generates correct download path', async () => {
    const table = shallowMount<IFileTable>(FileTable, { propsData: { items: [] }, stubs });

    expect(table.vm.safe('test.ext')).toBe('/files/documents/test.ext');
    expect(table.vm.safe('te st.ext')).toBe('/files/documents/te%20st.ext');

    await table.setProps({ directory: 'video' });

    expect(table.vm.safe('te s%2В../t.ext')).toBe('/files/video/te%20s%252%D0%92..%2Ft.ext');
    expect(table.vm.safe('русское_название_с_цифрами_102030.ext')).toBe(
      '/files/video/%D1%80%D1%83%D1%81%D1%81%D0%BA%D0%BE%D0%B5_%D0%BD%D0%B0%D0%B7%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5_%D1%81_%D1%86%D0%B8%D1%84%D1%80%D0%B0%D0%BC%D0%B8_102030.ext'
    );

    await table.setProps({ directory: 'documents' });

    expect(table.vm.safe('русское название с пробелами.ext')).toBe(
      '/files/documents/%D1%80%D1%83%D1%81%D1%81%D0%BA%D0%BE%D0%B5%20%D0%BD%D0%B0%D0%B7%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5%20%D1%81%20%D0%BF%D1%80%D0%BE%D0%B1%D0%B5%D0%BB%D0%B0%D0%BC%D0%B8.ext'
    );
    expect(table.vm.safe('русское название с %2В../t.ext')).toBe(
      '/files/documents/%D1%80%D1%83%D1%81%D1%81%D0%BA%D0%BE%D0%B5%20%D0%BD%D0%B0%D0%B7%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5%20%D1%81%20%252%D0%92..%2Ft.ext'
    );
  });

  test('defines selected rows', async () => {
    const table = shallowMount<IFileTable>(FileTable, { propsData: { items }, stubs });
    await table.setData({ pageable: { pageSize: 2, pageNumber: 0 } });
    expect(table.vm.list).toEqual(items.slice(0, 2));
    await table.setData({ pageable: { pageSize: 2, pageNumber: 2 } });
    expect(table.vm.list).toEqual(items.slice(4, 6));
    await table.setData({ pageable: { pageSize: 3, pageNumber: 1 } });
    expect(table.vm.list).toEqual(items.slice(3, 6));
    await table.setData({ pageable: { pageSize: 1, pageNumber: 5 } });
    expect(table.vm.list).toEqual(items.slice(5, 6));
  });

  test('renders download link', async () => {
    const DataTable = {
      name: 'DataTable',
      template: '<div><a v-for="item in items" class="link" :download="item.name">link</a></div>',
      props: { items: Array },
    };

    const table = mount<IFileTable>(FileTable, {
      propsData: { items },
      stubs: {
        ...stubs.reduce((result, key: string) => ({ ...result, [key]: true }), {}),
        DataTable,
      },
    });
    await table.setData({ pageable: { pageSize: items.length, pageNumber: 0 } });
    const links = table.findAll('.link');

    expect(links).toHaveLength(items.length);
    items.forEach(({ name }, index) => {
      const link = links.at(index);
      expect(link.exists()).toBe(true);
      expect(link.attributes('download')).toBe(name);
    });
  });
});
