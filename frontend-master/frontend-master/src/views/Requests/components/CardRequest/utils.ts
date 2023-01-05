/* eslint-disable no-nested-ternary */
/* eslint-disable max-lines-per-function */
export const mapInnerForm = (rest) => {
  return {
    is_mechanized: rest.elevator_info_change.is_mechanized,
    elevator_info_insurance: rest.elevator_info_change.elevator_info_insurance.map((item) => ({
      ...item,
      editCode: item.editCode,
      doc_date: item.doc_date,
      doc_num: item.doc_num,
      document_type: {
        label: item.document_type,
        value: null,
      },
      validity_date: item.validity_date,
    })),
    elevator_info_hazardous_object: rest.elevator_info_change.elevator_info_hazardous_object.map((item) => ({
      ...item,
      editCode: item.editCode,
      doc_date: item.doc_date,
      doc_num: item.doc_num,
    })),
    elevator_info_mothballed_year: rest.elevator_info_change.elevator_info_mothballed_year.map((item) => ({
      ...item,
      editCode: item.editCode,
      year_val: item.value,
    })),
    elevator_info_product: rest.elevator_info_change.elevator_info_product.map((item) => ({
      ...item,
      editCode: item.editCode ? item.editCode : null,
      prod_type_id: item.prod_type_id,
      name_tnved: item.product_name,
      tnved: item.tnved,
      okpd2: item.okpd2,
      product: {
        label: item.okpd2_name,
        value: item.prod_type_id,
      },
    })),
    elevator_info_service: rest.elevator_info_change.elevator_info_service.map((item) => ({
      editCode: item.editCode,
      name: {
        label: item.name,
        value: item.code,
      },
    })),
    elevator_info_processing: rest.elevator_info_change.elevator_info_processing.map((item) => ({
      editCode: item.editCode,
      name: {
        label: item.name,
        value: item.code,
      },
    })),
    auto_in: rest.elevator_info_change.auto_in.value,
    auto_out: rest.elevator_info_change.auto_out.value,
    capacity_mothballed_tons: rest.elevator_info_change.capacity_mothballed_tons.value,
    capacity_tons: rest.elevator_info_change.capacity_tons.value,
    is_locomotive: rest.elevator_info_change.is_locomotive.value,
    is_locomotive_rent: rest.elevator_info_change.is_locomotive_rent.value,
    is_testing_laboratory: rest.elevator_info_change.is_testing_laboratory.value,
    loading_capacity_auto: rest.elevator_info_change.loading_capacity_auto.value,
    loading_capacity_wagons: rest.elevator_info_change.loading_capacity_wagons.value,
    loading_capacity_water: rest.elevator_info_change.loading_capacity_water.value,
    railway_capacity_wagons: rest.elevator_info_change.railway_capacity_wagons.value,
    railway_capacity_wagons_rent: rest.elevator_info_change.railway_capacity_wagons_rent.value,
    railway_in: rest.elevator_info_change.railway_in.value,
    railway_length: rest.elevator_info_change.railway_length.value,
    railway_length_rent: rest.elevator_info_change.railway_length_rent.value,
    railway_out: rest.elevator_info_change.railway_out.value,
    station: rest.elevator_info_change.station.value,
    water_in: rest.elevator_info_change.water_in.value,
    water_out: rest.elevator_info_change.water_out.value,
    elevator_site: rest.elevator_site_change.map((item) => ({
      editCode: item.editCode,
      address_name: item.address_name.value,
      cadastral_number: item.cadastral_number.value,
      capacity_tons: item.capacity_tons.value,
      act_commissioning_num: item.act_commissioning_num ? item.act_commissioning_num.value : '',
      own_rent_doc_num: item.own_rent_doc_num ? item.own_rent_doc_num.value : '',
      act_commissioning_date: item.act_commissioning_date
        ? typeof item.act_commissioning_date.value === 'string'
          ? new Date(item.act_commissioning_date.value.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1'))
          : item.act_commissioning_date.value
        : null,
      own_rent_doc_date: item.own_rent_doc_date
        ? typeof item.own_rent_doc_date.value === 'string'
          ? new Date(item.own_rent_doc_date.value.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1'))
          : item.own_rent_doc_date.value
        : null,
      own_rent: item.own_rent
        ? {
            label: item.own_rent.value,
            value: null,
          }
        : null,
      elevator_site_storage: item.elevator_site_storage
        ? {
            label: item.elevator_site_storage.value,
            value: null,
          }
        : null,
      granary_type: {
        label: item.granary_type.value,
        value: null,
      },
      year_construction: item.year_construction.value,
      year_last_reconstruction: item.year_last_reconstruction.value,
    })),
    ...rest,
  };
};

//ToDo: Remove!!!
export const mapInnerFormChangesData = (rest) => {
  return {
    ...rest,
    is_mechanized: rest.elevator_info_change.is_mechanized,
    elevator_info_insurance: rest.elevator_info_change.elevator_info_insurance.map((item) => ({
      ...item,
      editCode: item.editCode,
      doc_date: item.doc_date,
      doc_num: item.doc_num,
      document_type: {
        label: item.document_type,
        value: null,
      },
      validity_date: item.validity_date,
    })),
    elevator_info_hazardous_object: rest.elevator_info_change.elevator_info_hazardous_object.map((item) => ({
      ...item,
      editCode: item.editCode,
      doc_date: item.doc_date,
      doc_num: item.doc_num,
    })),
    elevator_info_mothballed_year: rest.elevator_info_change.elevator_info_mothballed_year.map((item) => ({
      ...item,
      editCode: item.editCode,
      year_val: item.value,
    })),
    elevator_info_product: rest.elevator_info_change.elevator_info_product.map((item) => ({
      ...item,
      editCode: item.editCode ? item.editCode : null,
      prod_type_id: item.prod_type_id,
      name_tnved: item.product_name,
      tnved: item.tnved,
      okpd2: item.okpd2,
      product: {
        label: item.okpd2_name,
        value: item.prod_type_id,
      },
    })),
    elevator_info_service: rest.elevator_info_change.elevator_info_service.map((item) => ({
      editCode: item.editCode,
      name: {
        label: item.name,
        value: item.code,
      },
    })),
    elevator_info_processing: rest.elevator_info_change.elevator_info_processing.map((item) => ({
      editCode: item.editCode,
      name: {
        label: item.name,
        value: item.code,
      },
    })),
    auto_in: rest.elevator_info_change.auto_in.value,
    auto_out: rest.elevator_info_change.auto_out.value,
    capacity_mothballed_tons: rest.elevator_info_change.capacity_mothballed_tons.value,
    capacity_tons: rest.elevator_info_change.capacity_tons.value,
    is_locomotive: rest.elevator_info_change.is_locomotive.value,
    is_locomotive_rent: rest.elevator_info_change.is_locomotive_rent.value,
    is_testing_laboratory: rest.elevator_info_change.is_testing_laboratory.value,
    loading_capacity_auto: rest.elevator_info_change.loading_capacity_auto.value,
    loading_capacity_wagons: rest.elevator_info_change.loading_capacity_wagons.value,
    loading_capacity_water: rest.elevator_info_change.loading_capacity_water.value,
    railway_capacity_wagons: rest.elevator_info_change.railway_capacity_wagons.value,
    railway_capacity_wagons_rent: rest.elevator_info_change.railway_capacity_wagons_rent.value,
    railway_in: rest.elevator_info_change.railway_in.value,
    railway_length: rest.elevator_info_change.railway_length.value,
    railway_length_rent: rest.elevator_info_change.railway_length_rent.value,
    railway_out: rest.elevator_info_change.railway_out.value,
    water_in: rest.elevator_info_change.water_in.value,
    water_out: rest.elevator_info_change.water_out.value,
    loading_capacity_auto_tons: rest.elevator_info_change.loading_capacity_auto_tons.value,
    loading_capacity_train_tons: rest.elevator_info_change.loading_capacity_train_tons.value,

    station: { ...rest.elevator_info_change.station, name: rest.elevator_info_change.station.value },
    elevator_site: rest.elevator_site_change.map((item) => ({
      editCode: item.editCode,
      address_name: item.address_name.value,
      address_add: item.address_add.value,
      cadastral_number: item.cadastral_number.value,
      capacity_tons: item.capacity_tons.value,
      act_commissioning_num: item.act_commissioning_num ? item.act_commissioning_num.value : '',
      own_rent_doc_num: item.own_rent_doc_num ? item.own_rent_doc_num.value : '',
      act_commissioning_date: item.act_commissioning_date
        ? typeof item.act_commissioning_date.value === 'string'
          ? new Date(item.act_commissioning_date.value.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1'))
          : item.act_commissioning_date.value
        : null,
      own_rent_doc_date: item.own_rent_doc_date
        ? typeof item.own_rent_doc_date.value === 'string'
          ? new Date(item.own_rent_doc_date.value.replace(/(\d{2})\.(\d{2})\.(\d{4})/, '$3-$2-$1'))
          : item.own_rent_doc_date.value
        : null,
      own_rent: item.own_rent
        ? {
            name: item.own_rent.value,
            code: null,
          }
        : null,
      elevator_site_storage: item.elevator_site_storage
        ? {
            name: item.elevator_site_storage.value,
            code: null,
          }
        : null,
      granary_type: {
        name: item.granary_type.value,
        code: null,
      },
      year_construction: item.year_construction.value,
      year_last_reconstruction: item.year_last_reconstruction.value,
    })),
  };
};

export const mapInnerFormFromOldRequest = (data) => {
  return {
    approval_request_type: {
      ...data.approval_request_type,
    },
    subject_id: data.subject_id,
    subject: {
      ...data.subject,
    },
    notes: data.notes,
    identity_doc: data.identity_doc
      ? {
          doc_date: data.identity_doc?.doc_date,
          id_number: data.identity_doc?.id_number,
          series: data.identity_doc?.series,
          type: {
            ...data.identity_doc?.type,
          },
        }
      : undefined,
    elevator_register_application: {
      elevator_site: (data.elevator_site || []).map((item) => ({
        act_commissioning: {
          doc_date: item.act_commissioning?.doc_date,
          doc_num: item.act_commissioning?.doc_num,
        },
        address: {
          address: item.address?.address,
          aoguid: item.address?.aoguid,
          country: { ...item.address?.country },
          full_address: item.address?.full_address,
        },
        cadastral_number: item.cadastral_number,
        capacity_tons: item.capacity_tons,
        elevator_site_ownership_document: {
          doc_type_id: item.elevator_site_ownership_document?.doc_type_id,
          document_type: {
            ...item.elevator_site_ownership_document?.document_type,
          },
          external_document: {
            ...item.elevator_site_ownership_document?.external_document,
          },
        },
        year_construction: item.year_construction,
        year_last_reconstruction: item.year_last_reconstruction,
        year_overhaul: item.year_overhaul,
        own_rent_document: {
          doc_date: item.own_rent_document?.doc_date,
          doc_num: item.own_rent_document?.doc_num,
        },
        own_rent: {
          ...item.own_rent,
        },
        granary_type: {
          ...item.granary_type,
        },
        elevator_site_storage: {
          ...item.elevator_site_storage,
        },
      })),
      basis_changes: data.basis_changes,
      elevator_info: {
        is_mechanized: data.elevator_info.is_mechanized,
        auto_in: data.elevator_info.auto_in,
        auto_out: data.elevator_info.auto_out,
        capacity: data.elevator_info.capacity,
        capacity_mothballed: data.elevator_info.capacity_mothballed,
        water_in: data.elevator_info.water_in,
        water_out: data.elevator_info.water_out,
        has_locomotive: data.elevator_info.has_locomotive,
        has_locomotive_rent: data.elevator_info.has_locomotive_rent,
        loading_capacity_auto: data.elevator_info.loading_capacity_auto,
        loading_capacity_auto_tons: data.elevator_info.loading_capacity_auto_tons,
        loading_capacity_train_tons: data.elevator_info.loading_capacity_train_tons,
        loading_capacity_wagons: data.elevator_info.loading_capacity_wagons,
        loading_capacity_water: data.elevator_info.loading_capacity_water,
        railway_capacity_wagons: data.elevator_info.railway_capacity_wagons,
        railway_capacity_wagons_rent: data.elevator_info.railway_capacity_wagons_rent,
        railway_in: data.elevator_info.railway_in,
        railway_length: data.elevator_info.railway_length,
        railway_length_rent: data.elevator_info.railway_length_rent,
        railway_out: data.elevator_info.railway_out,
        station: data.elevator_info.station ? { ...data.elevator_info.station } : undefined,
        elevator_info_hazardous_object: (data.elevator_info.elevator_info_hazardous_object || []).map((item) => ({
          doc_date: item.doc_date,
          doc_num: item.doc_num,
          external_document: {
            doc_date: item.external_document?.doc_date,
            doc_num: item.external_document?.doc_num,
          },
        })),
        elevator_info_insurance: (data.elevator_info.elevator_info_insurance || []).map((item) => ({
          doc_date: item?.doc_date,
          doc_num: item?.doc_num,
          document_type: {
            ...item.document_type,
          },
          external_document: {
            doc_date: item.external_document?.doc_date,
            doc_num: item.external_document?.doc_num,
          },
          validity_date: item.validity_date,
        })),
        elevator_info_mothballed_year: (data.elevator_info.elevator_info_mothballed_year || []).map((item) => ({
          year_val: item.year_val,
        })),
        elevator_info_processing: (data.elevator_info.elevator_info_processing || []).map((item) => ({
          code: item.code,
          id: item.id,
          name: { ...item.name },
        })),
        elevator_info_product: (data.elevator_info.elevator_info_product || []).map((item) => ({
          ...item,
        })),
        elevator_info_service: (data.elevator_info.elevator_info_service || []).map((item) => ({
          addition: item.addition,
          name: { ...item.name },
          service_type: { ...item.service_type },
        })),
      },
    },
    elevator_site: (data.elevator_site || []).map((item) => ({
      act_commissioning: {
        doc_date: item.act_commissioning?.doc_date,
        doc_num: item.act_commissioning?.doc_num,
      },
      address: {
        address: item.address?.address,
        aoguid: item.address?.aoguid,
        country: { ...item.address?.country },
        full_address: item.address?.full_address,
      },
      cadastral_number: item.cadastral_number,
      capacity_tons: item.capacity_tons,
      elevator_site_ownership_document: {
        doc_type_id: item.elevator_site_ownership_document?.doc_type_id,
        document_type: {
          ...item.elevator_site_ownership_document?.document_type,
        },
        external_document: {
          ...item.elevator_site_ownership_document?.external_document,
        },
      },
      year_construction: item.year_construction,
      year_last_reconstruction: item.year_last_reconstruction,
      year_overhaul: item.year_overhaul,
      own_rent_document: {
        doc_date: item.own_rent_document?.doc_date,
        doc_num: item.own_rent_document?.doc_num,
      },
      own_rent: {
        ...item.own_rent,
      },
      granary_type: {
        ...item.granary_type,
      },
      elevator_site_storage: {
        ...item.elevator_site_storage,
      },
    })),
    basis_changes: data.basis_changes,
    elevator_info: {
      is_mechanized: data.elevator_info.is_mechanized,
      auto_in: data.elevator_info.auto_in,
      auto_out: data.elevator_info.auto_out,
      capacity: data.elevator_info.capacity,
      capacity_mothballed: data.elevator_info.capacity_mothballed,
      water_in: data.elevator_info.water_in,
      water_out: data.elevator_info.water_out,
      has_locomotive: data.elevator_info.has_locomotive,
      has_locomotive_rent: data.elevator_info.has_locomotive_rent,
      loading_capacity_auto: data.elevator_info.loading_capacity_auto,
      loading_capacity_auto_tons: data.elevator_info.loading_capacity_auto_tons,
      loading_capacity_train_tons: data.elevator_info.loading_capacity_train_tons,
      loading_capacity_wagons: data.elevator_info.loading_capacity_wagons,
      loading_capacity_water: data.elevator_info.loading_capacity_water,
      railway_capacity_wagons: data.elevator_info.railway_capacity_wagons,
      railway_capacity_wagons_rent: data.elevator_info.railway_capacity_wagons_rent,
      railway_in: data.elevator_info.railway_in,
      railway_length: data.elevator_info.railway_length,
      railway_length_rent: data.elevator_info.railway_length_rent,
      railway_out: data.elevator_info.railway_out,
      station: data.elevator_info.station ? { ...data.elevator_info.station } : undefined,
      elevator_info_hazardous_object: (data.elevator_info.elevator_info_hazardous_object || []).map((item) => ({
        doc_date: item.doc_date,
        doc_num: item.doc_num,
        external_document: {
          doc_date: item.external_document?.doc_date,
          doc_num: item.external_document?.doc_num,
        },
      })),
      elevator_info_insurance: (data.elevator_info.elevator_info_insurance || []).map((item) => ({
        doc_date: item.doc_date,
        doc_num: item.doc_num,
        document_type: {
          ...item.document_type,
        },
        external_document: {
          doc_date: item.external_document?.doc_date,
          doc_num: item.external_document?.doc_num,
        },
        validity_date: item.validity_date,
      })),
      elevator_info_mothballed_year: (data.elevator_info.elevator_info_mothballed_year || []).map((item) => ({
        year_val: item.year_val,
      })),
      elevator_info_processing: (data.elevator_info.elevator_info_processing || []).map((item) => ({
        code: item.code,
        id: item.id,
        name: { ...item.name },
      })),
      elevator_info_product: (data.elevator_info.elevator_info_product || []).map((item) => ({
        ...item,
      })),
      elevator_info_service: (data.elevator_info.elevator_info_service || []).map((item) => ({
        addition: item.addition,
        name: { ...item.name },
        service_type: { ...item.service_type },
      })),
    },
  };
};
