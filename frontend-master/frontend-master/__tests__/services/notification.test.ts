import NotificationService from '@/services/notification';
import set from 'lodash/set';

describe('notification service', () => {
  test('find notifications', async () => {
    const ctx: any = {};
    set(
      ctx,
      '$axios.post',
      jest.fn(() => ({ data: { content: [] } }))
    );
    const service = new NotificationService(ctx);

    const response = await service.find({});
    expect(ctx.$axios.post).toBeCalled();
    expect(response.data).toEqual([]);
  });

  test('get notifications count', async () => {
    const ctx: any = {};
    set(
      ctx,
      '$axios.get',
      jest.fn(() => ({ data: 3 }))
    );
    set(ctx, '$store.commit', jest.fn());
    const service = new NotificationService(ctx);
    const { data } = await service.getCount();
    expect(data).toBe(3);
  });

  test('mark notification as read', async () => {
    const ctx: any = {};
    set(
      ctx,
      '$axios.get',
      jest.fn(() => ({ data: 3 }))
    );
    set(
      ctx,
      '$axios.post',
      jest.fn(() => Promise.resolve({}))
    );
    set(ctx, '$store.commit', jest.fn());
    const service = new NotificationService(ctx);
    await service.markAsRead(2);
    expect(ctx.$axios.post).toBeCalledWith('/api/notification/markAsRead', { id: 2 });
  });

  test('find violations', async () => {
    const ctx: any = {};
    set(
      ctx,
      '$axios.post',
      jest.fn(() => ({ data: { content: [] } }))
    );
    const service = new NotificationService(ctx);

    const response = await service.VIOLATION.find({});
    expect(ctx.$axios.post).toBeCalled();
    expect(response.data).toEqual([]);
  });

  test('find one violation', async () => {
    const ctx: any = {};
    const data = {
      id: 2,
      created: '12.12.2012 12:12',
      difference: 'difference',
      subject: {
        subject_id: 2,
        name: 'name',
      },
      violation_type: {
        id: 1,
        code: 'CODE',
        name: 'name',
      },
    };
    set(
      ctx,
      '$axios.get',
      jest.fn(() => ({ data }))
    );
    const service = new NotificationService(ctx);

    const response = await service.VIOLATION.findOne(2);
    expect(ctx.$axios.get).toBeCalled();
    expect(response.data.toJSON()).toEqual({
      created: '2012-12-12T08:12:00.000Z',
      difference: 'difference',
      id: 2,
      subject: {
        id: 2,
        name: 'name',
      },
      type: {
        code: 'CODE',
        id: 1,
        name: 'name',
      },
    });
  });
});
