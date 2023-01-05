import Versions from '@/services/versions';

describe.skip('version service', () => {
  test('returns empty list', async () => {
    const service = new Versions({} as any);

    const actual = await service.getVersionList([]);
    expect(actual).toHaveLength(0);
  });

  test('returns valid list', async () => {
    const service = new Versions({} as any);

    const actual = await service.getVersionList([
      {
        id: 'approval-template-service',
        name: 'some name',
        callback: () => Promise.resolve({ data: '2.1' } as any),
      },
      {
        id: 'division-service',
        name: 'some name 2',
        callback: () => '2.2',
      },
      {
        id: 'authorization-service',
        callback: () => '2.3',
      },
    ]);

    expect(actual).toHaveLength(3);
    expect(actual).toEqual([
      {
        name: 'some name (approval-template-service)',
        id: 'approval-template-service',
        version: '2.1',
        active: true,
      },
      {
        name: 'some name 2 (division-service)',
        id: 'division-service',
        version: '2.2',
        active: true,
      },
      {
        name: 'authorization-service',
        id: 'authorization-service',
        version: '2.3',
        active: true,
      },
    ]);
  });

  test('manages invalid results', async () => {
    const service = new Versions({} as any);

    const actual = await service.getVersionList([
      {
        id: 'approval-template-service',
        name: 'some name',
        callback: () => Promise.reject({ data: '2.1' } as any),
      },
      {
        id: 'division-service',
        name: 'some name 2',
        callback: () => '2.2invalid',
      },
      {
        id: 'authorization-service',
        callback: () => '2.3',
      },
    ]);

    expect(actual).toHaveLength(3);
    expect(actual[0].version).toBe('Timeout (15s)');
    expect(actual[1].version).toBe('Ошибка получения версии');
    expect(actual[2].version).toBe('2.3');
  });

  test('manages timeout errors', async () => {
    const service = new Versions({} as any);

    const actual = await service.getVersionList([
      {
        id: 'approval-template-service',
        name: 'some name',
        callback: () => Promise.reject({ code: 'ECONNABORTED' } as any),
      },
      {
        id: 'division-service',
        name: 'some name 2',
        callback: () => '2.2invalid',
      },
      {
        id: 'authorization-service',
        callback: () => '2.3',
      },
    ]);

    expect(actual).toHaveLength(3);
    expect(actual[0].version).toBe('Timeout (15s)');
    expect(actual[1].version).toBe('Ошибка получения версии');
    expect(actual[2].version).toBe('2.3');
  });
});
