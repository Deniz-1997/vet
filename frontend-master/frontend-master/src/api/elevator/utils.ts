import isEmpty from 'lodash/isEmpty';

/* eslint-disable max-lines-per-function */
export const mapInnerForm = (data: any) => ({
  ...data,
  elevator_site: (data.elevator_register_application?.elevator_site || []).map((item) => ({
    ...item,
    own_rent: {
      ...item.elevator_site_ownership_document?.document_type,
    },
    own_rent_document: {
      ...item.elevator_site_ownership_document?.external_document,
    },
    elevator_site_storage: {
      ...item.elevator_site_storage?.method,
    },
  })),
  elevator_info: data.elevator_register_application?.elevator_info
    ? {
        ...(data.elevator_register_application?.elevator_info || {}),
        elevator_info_insurance: (data.elevator_register_application?.elevator_info || {}).elevator_info_insurance.map(
          (item) => ({
            ...item,
            doc_num: item.external_document?.doc_num,
            doc_date: item.external_document?.doc_date,

            document_type: {
              ...item.document_type,
              label: item.document_type.name,
            },
          })
        ),
        elevator_info_hazardous_object: (
          data.elevator_register_application?.elevator_info || {}
        ).elevator_info_hazardous_object.map((item) => ({
          doc_num: item.external_document.doc_num,
          doc_date: item.external_document.doc_date,
          ...item,
        })),
        elevator_info_service: (data.elevator_register_application?.elevator_info || {}).elevator_info_service
          .filter((item) => item && !isEmpty(item) && Object.keys(item).some((key) => !!item[key]))
          .map((item) => ({
            ...item,
            name: {
              label: item.service_type.name,
              code: item.service_type.id,
            },
          })),
        elevator_info_processing: (data.elevator_register_application?.elevator_info || {}).elevator_info_processing
          .filter((item) => item && !isEmpty(item) && Object.keys(item).some((key) => !!item[key]))
          .map((item) => ({
            ...item,
            name: {
              label: item.name,
              code: item.id,
            },
          })),
        elevator_info_product: (data.elevator_register_application?.elevator_info || {}).elevator_info_product.map(
          (item) =>
            item.okpd2
              ? {
                  label: item.okpd2.name,
                  value: {
                    id: item?.okpd2?.id ?? item?.id,
                    okpd2: {
                      id: item?.okpd2?.id ?? item?.id,
                      code: item.okpd2.code,
                      name: item.okpd2.name,
                    },
                    tnved: {
                      id: item.tnved?.id,
                      code: item.tnved?.code ?? item.tnved,
                      name: item?.tnved?.name ?? item.name_tnved,
                    },
                  },
                }
              : {
                  label: item.name,
                  value: {
                    okpd2: {
                      id: item.id,
                      code: item.code,
                      name: item.name,
                    },
                    tnved: {
                      id: item.tnved?.[0]?.id,
                      code: item.tnved?.[0]?.code,
                      name: item.tnved?.[0]?.name,
                    },
                  },
                }
        ),
      }
    : {},
});

// eslint-disable-next-line max-lines-per-function
export const mapInnerChangeForm = (data) => ({
  ...data,
  elevator_site: data.elevator_site_change,
  elevator_info: {
    ...data.elevator_info_change,
    elevator_info_insurance: data.elevator_info_change.elevator_info_insurance.map((item) => ({
      ...item,
      document_type: {
        ...item.docuemnt_type,
        label: item.document_type.name,
      },
    })),
    elevator_info_service: data.elevator_info_change.elevator_info_service.map((item) => ({
      ...item,
      name: {
        label: item.name,
        code: item.id,
      },
    })),
    elevator_info_processing: data.elevator_info_change.elevator_info_processing.map((item) => ({
      ...item,
      name: {
        label: item.name,
        code: item.id,
      },
    })),
    elevator_info_product: data.elevator_info_change.elevator_info_product.map((item) => ({
      ...item,
      prod_type_id: item.id,
      code: item.okpd2.code,
      name_tnved: item.name,
      name: item.okpd2.name,
      tnved: item.tnved,
      label: item.okpd2.name,
      value: item.okpd2.code,
      okpd2: item.okpd2.code,
      product_name: item.name,
      product: {
        ...item,
        name: item.okpd2.name,
        code: item.okpd2.code,
        label: item.okpd2.name,
        value: item.okpd2.code,

        prod_type_id: item.id,
        name_tnved: item.name,
        tnved: item.tnved,
        okpd2: item.okpd2.code,
        product_name: item.name,
        product: {
          name: item.okpd2.name,
          code: item.okpd2.code,
          label: item.okpd2.name,
          value: item.okpd2.code,
        },
      },
    })),
  },
  subject: {
    ...data.subject,
  },
});

// eslint-disable-next-line max-lines-per-function
export const mapInnerFormForOrganization = (data) => ({
  ...data,
  elevator_site: data.elevator_site.map((item) => ({
    ...item,
    own_rent: {
      ...item.elevator_site_ownership_document?.document_type,
    },
    own_rent_document: {
      ...item.elevator_site_ownership_document?.external_document,
    },
    elevator_site_storage: {
      ...item.elevator_site_storage?.method,
    },
  })),
  elevator_info: {
    ...data.elevator_info,
    elevator_info_insurance: data.elevator_info.elevator_info_insurance.map((item) => ({
      ...item,
      doc_num: item.external_document?.doc_num,
      doc_date: item.external_document?.doc_date,

      document_type: {
        ...item.document_type,
        label: item.document_type.name,
      },
    })),
    elevator_info_hazardous_object: data.elevator_info.elevator_info_hazardous_object.map((item) => ({
      doc_num: item.external_document.doc_num,
      doc_date: item.external_document.doc_date,
      ...item,
    })),
    elevator_info_service: data.elevator_info.elevator_info_service.map((item) => ({
      ...item,
      name: {
        label: item.service_type.name,
        code: item.service_type.id,
      },
    })),
    elevator_info_processing: data.elevator_info.elevator_info_processing.map((item) => ({
      ...item,
      name: {
        label: item.name,
        code: item.id,
      },
    })),
    elevator_info_product: data.elevator_info.elevator_info_product.map((item) =>
      item.okpd2
        ? {
            label: item.okpd2.name,
            value: {
              id: item?.okpd2?.id ?? item?.id,
              okpd2: {
                code: item.okpd2.code,
                name: item.okpd2.name,
              },
              tnved: {
                code: item.tnved?.code ?? item.tnved,
                name: item?.tnved?.name ?? item.name_tnved,
              },
            },
          }
        : {
            label: item.name,
            value: {
              okpd2: {
                id: item.id,
                code: item.code,
                name: item.name,
              },
              tnved: {
                id: item.tnved?.[0]?.id,
                code: item.tnved?.[0]?.code,
                name: item.tnved?.[0]?.name,
              },
            },
          }
    ),
  },
});

// eslint-disable-next-line max-lines-per-function
export const mapInnerFormRequestForChanges = ({ subject, ...data }) => ({
  subject_id: subject.subject_id,
  elevator_register_application: {
    elevator_registration_number: data.registration_number,
  },
  subject: subject,
  elevator_info: {
    ...data.elevator_info,
    is_mechanized: data.elevator_info.is_mechanized,
    elevator_info_hazardous_object: data.elevator_info.elevator_info_hazardous_object.map((item) => ({
      doc_num: item.external_document.doc_num,
      doc_date: item.external_document.doc_date,
    })),
    elevator_info_insurance: data.elevator_info.elevator_info_insurance.map((item) => ({
      ...item,
      document_type: {
        ...item.docuemnt_type,
        label: item.document_type.name,
      },
    })),
    elevator_info_service: data.elevator_info.elevator_info_service.map((item) => ({
      ...item,
      name: {
        label: item.service_type?.name,
        code: item.service_type?.id,
      },
    })),
    elevator_info_processing: data.elevator_info.elevator_info_processing.map((item) => ({
      ...item,
      name: {
        label: item.name,
        code: item.id,
      },
    })),
    elevator_info_product: data.elevator_info.elevator_info_product.map((item) => ({
      ...item,
      prod_type_id: item.id,
      code: item.okpd2.code,
      name_tnved: item.name,
      name: item.okpd2.name,
      tnved: item.tnved,
      label: item.okpd2.name,
      value: item.okpd2.code,
      okpd2: item.okpd2.code,
      product_name: item.name,
      product: {
        name: item.okpd2.name,
        code: item.okpd2.code,
        label: item.okpd2.name,
        value: item.okpd2.code,
      },
    })),
  },
  elevator_site: data.elevator_site,
});

// eslint-disable-next-line max-lines-per-function
export const mapForm = ({ subject, elevator_register_application, elevator_info, elevator_site, ...data }) => ({
  ...data,
  subject_id: subject.subject_id,

  elevator_register_application: {
    basis_changes: elevator_register_application.basis_changes,

    elevator_info: {
      is_mechanized: elevator_info.is_mechanized,
      elevator_info_id: elevator_info.elevator_info_id,

      elevator_info_hazardous_object: elevator_info.elevator_info_hazardous_object?.map((item) => ({
        elevator_info_hazardous_object_id: item?.elevator_info_hazardous_object_id
          ? { ...item?.elevator_info_hazardous_object_id }
          : null,
        external_document:
          item?.doc_num || item?.doc_date
            ? {
                id: item?.external_document?.id,
                doc_date: item.doc_date,
                doc_num: item.doc_num,
              }
            : null,
      })),

      elevator_info_insurance: elevator_info.elevator_info_insurance.map((item) => ({
        ...item,
        external_document: {
          id: item.doc_id ? item.doc_id : null,
          doc_date: item.doc_date ? item.doc_date : null,
          doc_num: item.doc_num ? item.doc_num : null,
        },
      })),

      elevator_info_service: elevator_info.elevator_info_service
        .filter((value) => value && !isEmpty(value) && Object.keys(value).some((key) => !!value[key]))
        .map((item) => ({
          service_type: {
            id: item.name.value ? item.name.value : item.name.code,
          },
          addition: item.addition,
        })),

      elevator_info_processing: elevator_info.elevator_info_processing
        .filter((value) => value && !isEmpty(value) && Object.keys(value).some((key) => !!value[key]))
        .map((item) => ({
          ...item,
          id: item.name.value ? item.name.value : Number(item.code),
          name: item.name.label ? item.name.label : item.name,
        })),

      elevator_info_mothballed_year: (elevator_info.elevator_info_mothballed_year || []).filter(
        (value) => value && !isEmpty(value) && Object.keys(value).some((key) => !!value[key])
      ),

      elevator_info_product: elevator_info.elevator_info_product
        .filter((item) => item?.value?.okpd2?.id && item?.value?.tnved?.id)
        .map(({ value: { okpd2, tnved } }) => ({
          okpd2_id: okpd2.id,
          tnved_id: tnved.id,
        })),

      capacity_mothballed: elevator_info.capacity_mothballed,

      testing_laboratory: elevator_info.testing_laboratory,

      loading_capacity_auto: elevator_info.loading_capacity_auto,

      loading_capacity_auto_tons: elevator_info.loading_capacity_auto_tons,

      loading_capacity_wagons: elevator_info.loading_capacity_wagons,

      loading_capacity_train_tons: elevator_info.loading_capacity_train_tons,

      railway_length: elevator_info.railway_length,

      railway_length_rent: elevator_info.railway_length_rent,

      railway_capacity_wagons: elevator_info.railway_capacity_wagons,

      railway_capacity_wagons_rent: elevator_info.railway_capacity_wagons_rent,

      has_locomotive: elevator_info.has_locomotive,

      station: elevator_info.station
        ? {
            id: elevator_info?.station.id,
          }
        : null,

      has_locomotive_rent: elevator_info.has_locomotive_rent,

      loading_capacity_water: elevator_info.loading_capacity_water,

      railway_in: elevator_info.railway_in,

      railway_out: elevator_info.railway_out,

      auto_in: elevator_info.auto_in,

      auto_out: elevator_info.auto_out,

      water_in: elevator_info.water_in,

      water_out: elevator_info.water_out,
    },
    elevator_site: elevator_site.map((item) => {
      const result = {
        ...item,
      };
      result.act_commissioning =
        item.act_commissioning?.doc_date || item.act_commissioning?.doc_num ? { ...item.act_commissioning } : null;
      result.elevator_site_ownership_document = {
        external_document: {
          id: item.own_rent_document ? item.own_rent_document.id : null,
          doc_date: item.own_rent_document.doc_date ? item.own_rent_document.doc_date : null,
          doc_num: item.own_rent_document.doc_num ? item.own_rent_document.doc_num : null,
        },
        document_type: item.own_rent ? { ...item.own_rent } : null,
      };
      result.elevator_site_storage = {
        method: {
          ...item?.elevator_site_storage,
        },
      };

      return result;
    }),
  },
});
