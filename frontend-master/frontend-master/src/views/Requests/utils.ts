/* eslint-disable max-lines-per-function */
import moment from 'moment';

export const mapInnerForm = ({ approval_request_type, elevator_register_application, subject, ...rest }) => {
  return {
    subjectName: {
      name: subject.name,
      code: subject.subject_id,
      // ...elevator_register_application.subject
    },
    request_id: rest.request_id,
    typeName: {
      label: approval_request_type.name,
      value: approval_request_type.code,
    },
    approval_request_type: {
      label: approval_request_type.name,
      value: approval_request_type.code,
    },
    request_status: rest.request_status
      ? rest.request_status.code
      : {
          code: 1,
          name: 'Черновик',
        },
    basis_changes: elevator_register_application.basis_changes ? elevator_register_application.basis_changes : '',
    subject: {
      ...subject,
    },
    totalCapacity: elevator_register_application.elevator_info.capacity_tons,
    elevator_id: elevator_register_application.elevator_id,
    ...elevator_register_application.elevator_info,
    elevator_info_insurance: elevator_register_application.elevator_info.elevator_info_insurance.map((item) => ({
      ...item,
      editCode: item.editCode ? item.editCode : null,
      doc_date: item.doc_date,
      doc_num: item.doc_num,
      document_type: {
        label: item.document_type.name,
        value: item.document_type.code,
      },
      validity_date: item.validity_date,
    })),
    elevator_info_mothballed_year: elevator_register_application.elevator_info.elevator_info_mothballed_year.map(
      (item) => ({
        ...item,
        year_val: item.year_val,
      })
    ),
    elevator_info_service: elevator_register_application.elevator_info.elevator_info_service.map((item) => ({
      name: {
        label: item.name,
        value: item.id,
      },
      addition: item.addition ? item.addition : null,
    })),
    elevator_info_processing: elevator_register_application.elevator_info.elevator_info_processing.map((item) => ({
      name: {
        label: item.name,
        value: item.code,
      },
    })),
    elevator_info_product: elevator_register_application.elevator_info.elevator_info_product.map((item) => ({
      product: {
        label: item.okpd2_name,
        value: item.prod_type_id,
        prod_type_id: item.prod_type_id,
        name_tnved: item.product_name,
        tnved: item.tnved,
        okpd2: item.okpd2,
      },
    })),
    elevator_site: elevator_register_application.elevator_site.map((item) => ({
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
        id: item.granary_type.id,
        ...item.granary_type,
      },
    })),
    elevator_register_application: {
      ...elevator_register_application,
    },
    ...rest,
  };
};

export const mapInnerFormForEdit = ({ elevator_info, subject, requestType, ...rest }) => {
  return {
    ...rest,
    ...elevator_info,
    subjectName: {
      name: subject ? subject.name : rest.name,
      code: rest.subject_id,
    },
    typeName: {
      label: requestType.label,
      value: requestType.value,
    },
    elevator_info_hazardous_object: elevator_info.elevator_info_hazardous_object,
    elevator_info_insurance: elevator_info[0].elevator_info_insurance.map((item) => ({
      ...item,
      editCode: item.editCode ? item.editCode : null,
      doc_date: item.doc_date,
      doc_num: item.doc_num,
      document_type: {
        label: item.document_type.name,
        value: item.document_type.code,
      },
      validity_date: item.validity_date,
    })),
    elevator_info_product: elevator_info[0].elevator_info_product.map((item) => ({
      product: {
        label: item.okpd2_name,
        value: item.prod_type_id,
        prod_type_id: item.prod_type_id,
        name_tnved: item.product_name,
        tnved: item.tnved,
        okpd2: item.okpd2,
      },
    })),
    elevator_site: rest.elevator_site.map((item) => ({
      ...item,
      address_name: item.address_name,
      address_add: item.address_add,
      act_commissioning_date:
        typeof item.act_commissioning_date === 'string'
          ? new Date(item.act_commissioning_date.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1'))
          : item.act_commissioning_date,
      own_rent_doc_date:
        typeof item.own_rent_doc_date === 'string'
          ? new Date(item.own_rent_doc_date.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1'))
          : item.own_rent_doc_date,
    })),
    elevator_info_service: elevator_info[0].elevator_info_service.map((item) => ({
      name: {
        label: item.name,
        value: item.code,
      },
      addition: item.addition ? item.addition : null,
    })),
    station: elevator_info.station
      ? {
          name: elevator_info.station.name,
          code: elevator_info.station.code,
          id: elevator_info.station.id,
        }
      : null,
    elevator_info_processing: elevator_info[0].elevator_info_processing.map((item) => ({
      name: {
        label: item.name,
        value: item.code,
      },
    })),
  };
};

export const mapForm = ({
  typeName,
  requestSiteList: _,
  elevator_register_application: __,
  elevator_info_product,
  elevator_info_hazardous_object,
  elevator_info_insurance,
  elevator_info_service,
  elevator_info_processing,
  elevator_site,
  elevator_info_mothballed_year,
  basis_changes,
  railway_in,
  is_locomotive_rent,
  totalCapacity,
  capacity_mothballed_tons,
  railway_length_rent,
  railway_length,
  railway_capacity_wagons_rent,
  railway_capacity_wagons,
  ...rest
}: any) => {
  const defaultInfo = {
    is_testing_laboratory: false,
    is_locomotive: false,
    is_locomotive_rent: false,
    auto_in: false,
    water_in: false,
    water_out: false,
    railway_in: false,
    railway_out: false,
    elevator_info_service: [],
    elevator_info_processing: [],
    elevator_info_hazardous_object: [],
    elevator_info_mothballed_year: [],
    elevator_info_insurance: [],
    elevator_info_product: [],
    elevator_site: [],
  };

  return {
    approval_request_type: {
      id: typeName ? Number(typeName.value) : null,
      name: typeName ? typeName.label : null,
    },
    subject: {
      ...rest.subject,
      address: rest.address ? rest.address : null,
      inn: rest.inn ? rest.inn : null,
      inn_kpp: rest.inn_kpp ? rest.inn_kpp : null,
      kpp: rest.kpp ? rest.kpp : null,
      name: rest.name ? rest.name : null,
      opf: rest.opf ? rest.opf : null,
      short_name: rest.short_name ? rest.short_name : null,
      startDate: rest.startDate ? rest.startDate : null,
      startTime: rest.startTime ? rest.startTime : null,
      start_date: rest.start_date ? rest.start_date : null,
      subject_id: rest.subject_id ? rest.subject_id : null,
      subject_not_registered: rest.subject_not_registered ? rest.subject_not_registered : null,
      subject_type: rest.subject_type ? rest.subject_type : null,
      subject_data: undefined,
    },
    subject_id: rest.subject.subject_id,
    request_id: rest ? rest.request_id : null,
    elevator_register_application: {
      elevator_id: rest ? rest.elevator_id : null,
      elevator_site:
        elevator_site.length > 0 &&
        elevator_site.map((item) => {
          if (item) {
            return {
              ...item,
              act_commissioning: item.act_commissioning.doc_date
                ? {
                    doc_date:
                      typeof item.act_commissioning.doc_date === 'string'
                        ? item.act_commissioning.doc_date
                        : moment(item.act_commissioning.doc_date).format('DD.MM.YYYY'),
                    doc_num: item.act_commissioning.doc_num,
                  }
                : null,
              address_add: item.address_add,
              address_name: item.address_name,
              capacity_tons: Number(item.capacity_tons),
              elevator_site_storage: {
                name: item.elevator_site_storage ? item.elevator_site_storage.name : '',
                id: item.elevator_site_storage ? Number(item.elevator_site_storage.code) : '',
              },
              own_rent_doc_num: item.own_rent_doc_num,
              year_construction: item.year_construction,
              year_last_reconstruction: item.year_last_reconstruction,
              own_rent: {
                name: item.own_rent ? item.own_rent.name : '',
                code: item.own_rent ? item.own_rent.code : '',
              },
              cadastral_number: item.cadastral_number,
              granary_type: {
                name: item.granary_type ? item.granary_type.name : '',
                id: item.granary_type ? item.granary_type.id : '',
              },
            };
          }
          return {};
        }),
      elevator_info: {
        ...defaultInfo,
        ...Object.keys(rest).reduce((result, key) => {
          return {
            ...result,
            [key]: Number(String(rest[key])) || rest[key],
          };
        }, {}),
        elevator_info_product:
          elevator_info_product.length > 0 &&
          elevator_info_product.map((item) => ({
            okpd2: item.product ? item.product.value : item.okpd2,
            okpd2_name: item.product ? item.product.label : item.label,
            prod_type_id: item.product ? item.product.prod_type_id : item.prod_type_id,
            product_name: item.product ? item.product.name_tnved : item.name_tnved,
            tnved: item.product ? item.product.tnved : item.tnved,
          })),
        elevator_info_service:
          elevator_info_service.length > 0 &&
          elevator_info_service.map((item) => ({
            ...item,
            name: item.name ? item.name.label : '',
            id: item.name ? item.name.value : '',
          })),
        elevator_info_processing:
          elevator_info_processing.length > 0 &&
          elevator_info_processing.map((item) => ({
            ...item,
            name: item.name ? item.name.label : '',
            id: item.name ? item.name.value : '',
          })),
        elevator_info_mothballed_year:
          elevator_info_mothballed_year.length > 0 &&
          elevator_info_mothballed_year.map((item) => ({
            ...item,
            year_val: Number(item.year_val),
          })),
        elevator_info_insurance:
          elevator_info_insurance.length > 0 &&
          elevator_info_insurance.map((item) => ({
            ...item,
            doc_date: typeof item.doc_date === 'string' ? item.doc_date : moment(item.doc_date).format('DD.MM.YYYY'),
            doc_num: item.doc_num,
            document_type: {
              name: item.document_type ? item.document_type.label : '',
              id: item.document_type ? item.document_type.value : '',
            },
            validity_date:
              typeof item.validity_date === 'string'
                ? item.validity_date
                : moment(item.validity_date).format('DD.MM.YYYY'),
          })),
        elevator_info_hazardous_object:
          elevator_info_hazardous_object.length > 0 &&
          elevator_info_hazardous_object.map((item) => ({
            ...item,
            doc_date: typeof item.doc_date === 'string' ? item.doc_date : moment(item.doc_date).format('DD.MM.YYYY'),
          })),
        railway_in: railway_in,
        is_locomotive_rent: is_locomotive_rent,
        capacity_tons: totalCapacity ? totalCapacity : 0,
        capacity_mothballed_tons: capacity_mothballed_tons ? capacity_mothballed_tons : 0,
        railway_capacity_wagons: railway_capacity_wagons ? railway_capacity_wagons : 0,
        railway_capacity_wagons_rent: railway_capacity_wagons_rent ? railway_capacity_wagons_rent : 0,
        railway_length: railway_length ? railway_length : 0,
        railway_length_rent: railway_length_rent ? railway_length_rent : 0,
      },
      basis_changes: basis_changes,
    },
  };
};
