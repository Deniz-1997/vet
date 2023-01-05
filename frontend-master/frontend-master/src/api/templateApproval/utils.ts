const mapInnerForm = (data) => ({
  ...data,
  stages: data.stages.map((item, index) => ({
    ...item,
    number: index + 1,
    subject_name: item.subject?.subject_data?.name,
    subject_division_name: item.subject_division.name
  })) 
});

export default mapInnerForm;