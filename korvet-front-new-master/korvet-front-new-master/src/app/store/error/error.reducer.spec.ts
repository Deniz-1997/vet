import {errorReducer, initialState} from './error.reducer';

describe('Error Reducer', () => {
  describe('unknown action', () => {
    it('should return the initial state', () => {
      const action = {} as any;

      const result = errorReducer(initialState, action);

      expect(result).toBe(initialState);
    });
  });
});
