import { Debounce, Throttle, Memoize } from '@/utils/global/decorators/method';

describe('method decorators', () => {
  test('debounce', () => {
    const mockWrapper = jest.fn(() => jest.fn() as any);
    const mockValue = jest.fn();
    let mockDescriptor: any = { value: mockValue };
    const injector = Debounce(600, mockWrapper);

    injector({}, '', mockDescriptor);
    expect(mockWrapper).toBeCalledWith(mockValue, 600);
  });

  test('throttle', () => {
    const mockWrapper = jest.fn();
    const mockValue = jest.fn();
    let mockDescriptor: any = { value: mockValue };
    const injector = Throttle(666, mockWrapper);

    injector({}, '', mockDescriptor);
    expect(mockWrapper).toBeCalledWith(mockValue, 666);
  });

  test('memoize', () => {
    const mockResolver = jest.fn();
    const mockWrapper: any = jest.fn();
    const mockValue = jest.fn();
    let mockDescriptor: any = { value: mockValue };
    const injector = Memoize(mockResolver, mockWrapper);

    injector({}, '', mockDescriptor);
    expect(mockWrapper).toBeCalledWith(mockValue, mockResolver);
  });
});
