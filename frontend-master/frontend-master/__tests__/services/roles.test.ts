import { FilterOut } from '@/services/mappers/common';
import { RoleItem } from '@/services/mappers/roles';
import Roles from '@/services/roles';
import { fixtures } from './__fixtures__/roles';

let ctx;

beforeEach(() => {
  ctx = {
    $axios: {
      get: jest.fn(() => Promise.resolve({ data: fixtures[0] })),
      post: jest.fn(() => Promise.resolve({ data: { content: fixtures } })),
    }
  };
});

describe('roles service', () => {
  test('find', async () => {
    const service = new Roles(ctx);
    const actual = await service.find({});

    expect(ctx.$axios.post).toBeCalled();
    expect(ctx.$axios.post.mock.calls[0][1]).toBeInstanceOf(FilterOut);
    expect(actual.data).toHaveLength(3);
    expect(actual.data[0]).toBeInstanceOf(RoleItem);
  });

  test('findOne', async () => {
    const service = new Roles(ctx);
    const actual = await service.findOne(2);

    expect(ctx.$axios.get).toBeCalledWith('/api/security/role/2');
    expect(actual.data).toBeInstanceOf(RoleItem);
  });
});
