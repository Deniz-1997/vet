/**
 * Create an action.
 * @param {String} type Action type.
 * @param {Function} service API request.
 */
 export function handleAsyncAction(type, service) {
  return async (context, options) => {
    const data = await service(options);
    context.commit(type, data);
    return data;
  };
}
