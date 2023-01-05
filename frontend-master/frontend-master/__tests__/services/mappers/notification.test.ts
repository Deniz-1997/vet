import { ENotificationObjectType, ENotificationStatus } from '@/services/enums/notification';
import { NotificationItem } from '@/services/mappers/notification';
import { checkMapper } from './__utils__/checkMapper';

describe('notifications mappers', () => {
  test('NotificationItem', () => {
    checkMapper(
      NotificationItem,
      {
        id: 2,
        created: '12.12.2012 12:12',
        message: 'test',
        subject: { subject_id: 5, short_name: 'test_subject' } as any,
        object_id: 2,
        type: { name: 'test_object', code: ENotificationObjectType.VIOLATION } as any,
        status: { id: 1, name: 'test_status', code: ENotificationStatus.NEW } as any,
      },
      {
        id: 2,
        created: '2012-12-12T08:12:00.000Z',
        message: 'test',
        object: { id: 2, name: 'test_object', type: ENotificationObjectType.VIOLATION },
        status: { id: 1, name: 'test_status', code: ENotificationStatus.NEW },
        subject: { id: 5, name: 'test_subject' },
      }
    );
    checkMapper(
      NotificationItem,
      {
        id: 12,
        created: '12.12.2022 22:12',
        message: 'test123',
        subject: { subject_id: 51, name: 'test_subject1' } as any,
        object_id: 21,
        type: { name: 'test_object1', code: ENotificationObjectType.VIOLATION } as any,
        status: { id: 11, name: 'test_status1', code: ENotificationStatus.READ } as any,
      },
      {
        id: 12,
        created: '2022-12-12T19:12:00.000Z',
        message: 'test123',
        object: { id: 21, name: 'test_object1', type: ENotificationObjectType.VIOLATION },
        status: { id: 11, name: 'test_status1', code: ENotificationStatus.READ },
        subject: { id: 51, name: 'test_subject1' },
      }
    );
  });
});
