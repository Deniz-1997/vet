export const mapInnerForm = (data) =>
  data.map((item) => ({
    ...item,
    root_division: {
      code: item.parent_division_id ? item.parent_division_id : null,
      name: item.root_division_name ? item.root_division_name : null,
    },
    division_staff: item.division_staff.map((item) => ({
      ...item,
      staff: {
        label: item.user_full_name,
        value: item.user_id,
      },
    })),
  }));
