const mapSubjectName = ({ name, subject_id }) => ({
  name,
  code: subject_id,
});

const mapTypeName = ({ type_name, type_id }) => ({
  label: type_name,
  code: type_id,
});

const mapApprovalRequestType = ({ type_name, type_id }) => ({
  label: type_name,
  code: type_id,
});

const mapRequestStatus = (status) => (status ? status.code : { code: 1, name: 'Черновик' });

const mapSelectableFromItem = ({ name, code }) => ({ label: name, value: code });

const mapElevatorInfo = (info) => ({
  ...info,
  elevator_info_insurance: info.elevator_info_insurance.map((item) => ({
    ...item,
    editCode: item.editCode || null,
    doc_date: item.doc_date,
    doc_num: item.doc_num,
    document_type: mapSelectableFromItem(item.document_type),
    validity_date: item.validity_date,
  })),
  elevator_info_mothballed_year: info.elevator_info_mothballed_year.map((item) => ({
    ...item,
    year_val: item.year_val,
  })),
  elevator_info_service: info.elevator_info_service.map((item) => ({
    name: mapSelectableFromItem(item),
  })),
  elevator_info_processing: info.elevator_info_processing.map((item) => ({
    name: mapSelectableFromItem(item),
  })),
  elevator_info_product: info.elevator_info_product.map((item) => ({
    prod_type_id: item.prod_type_id,
    name_tnved: item.product_name,
    tnved: item.tnved,
    okpd2: item.okpd2,
    product: {
      label: item.okpd2_name,
      value: item.prod_type_id,
    },
  })),
});

const mapElevatorSites = (list) =>
  list.map((item) => ({
    ...item,
    address_name: item.address_name,
    cadastral_number: item.cadastral_number,
    capacity_tons: item.capacity_tons,
    act_commissioning_date:
      typeof item.act_commissioning_date === 'string'
        ? new Date(item.act_commissioning_date.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1'))
        : item.act_commissioning_date,
    own_rent_doc_date:
      typeof item.own_rent_doc_date === 'string'
        ? new Date(item.own_rent_doc_date.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1'))
        : item.own_rent_doc_date,
    own_rent: item.own_rent
      ? {
          name: item.own_rent.name,
          code: item.own_rent.code,
        }
      : null,
    elevator_site_storage: item.elevator_site_storage
      ? {
          name: item.elevator_site_storage.name,
          code: item.elevator_site_storage.code,
        }
      : null,
    granary_type: {
      name: item.granary_type.name,
      code: item.granary_type.code,
    },
  }));

export const mapInnerForm = ({ elevator_request, elevator_info, elevator_site, subject, subject_id, ...rest }) => {
  return {
    subject_id: subject_id || subject.subject_id,
    subjectName: mapSubjectName(subject),
    typeName: mapTypeName(elevator_request),
    approval_request_type: mapApprovalRequestType(elevator_request),
    request_status: mapRequestStatus(rest.request_status),
    basis_changes: elevator_request.basis_changes || '',
    subject: { ...subject },
    totalCapacity: elevator_info.capacity_tons,
    elevator_id: rest.elevator_id,
    ...mapElevatorInfo(elevator_info),
    elevator_site: mapElevatorSites(elevator_site),
    ...rest,
  };
};
