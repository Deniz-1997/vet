/* eslint-disable max-lines */
import LoginRouter from '@/views/Login/Login.vue';
import EsiaLogin from '@/views/Login/EsiaLogin.vue';
import RequestsList from '@/views/Requests/RequestsList.vue';
import RequestCard from '@/views/Requests/components/CardRequest/CardRequest.vue';
import Approval from '@/views/ApplicationsApproval/Approval.vue';
import Templates from '@/views/ApprovalTemplates/Templates.vue';
import Lot from '@/views/Lot/Lot.vue';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import SdizList from '@/views/Sdiz/components/Pages/Default/List.vue';
import SdizCreate from '@/views/Sdiz/components/Pages/Default/Create.vue';
import SdizDetail from '@/views/Sdiz/components/Pages/Default/Detail.vue';
import NSI from '@/views/NSI/NSI.vue';
import NsiList from '@/views/NSI/components/NsiList/NsiList.vue';
import NsiCard from '@/views/NSI/components/NsiCard/NsiCard.vue';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import ImplementationDetail from '@/views/Gosmonitoring/components/Register/Implementation/Detail.vue';
import ImplementationList from '@/views/Gosmonitoring/components/Register/Implementation/List.vue';
import ImplementationCreate from '@/views/Gosmonitoring/components/Register/Implementation/Create.vue';
import ImplementationUpdate from '@/views/Gosmonitoring/components/Register/Implementation/Edit.vue';
import SubmittedByManufacturersList from '@/views/Gosmonitoring/components/Register/SubmittedByManufacturers/List.vue';
import ResearchRegisterList from '@/views/Gosmonitoring/components/Register/ResearchRegister/List.vue';
import ResearchRegisterDetail from '@/views/Gosmonitoring/components/Register/ResearchRegister/Detail.vue';
import ConductedResearchManufacturersList from '@/views/Gosmonitoring/components/Register/ConductedResearchManufacturers/List.vue';
import ResearchRegisterCreate from '@/views/Gosmonitoring/components/Register/ResearchRegister/Create.vue';
import ResearchRegisterEdit from '@/views/Gosmonitoring/components/Register/ResearchRegister/Edit.vue';
import Laboratories from '@/views/Laboratories/Laboratories.vue';
import Agents from '@/views/Agents/Agents.vue';
import NumbersForManufacturers from '@/views/NumbersForManufacturers/NumbersForManufacturers.vue';
import PasswordRecovery from '@/views/Login/PasswordRecovery.vue';
import Reserves from '@/views/Reserves/Reserves.vue';
import ReservesNumberSdiz from '@/views/Reserves/components/ReservesNumberSdiz.vue';
import ReservesNumberProducts from '@/views/Reserves/components/ReservesNumberProducts.vue';
import ReservesNumberLot from '@/views/Reserves/components/ReservesNumberLot.vue';
import Home from '@/views/Home/Home.vue';
import SdizElevatorList from '@/views/Sdiz/components/Pages/Elevator/List.vue';
import SdizElevatorCreate from '@/views/Sdiz/components/Pages/Elevator/Create.vue';
import LotElevatorList from '@/views/Lot/components/Pages/Elevator/List.vue';
import SdizGpbList from '@/views/Sdiz/components/Pages/Gpb/List.vue';
import SdizGpbCreate from '@/views/Sdiz/components/Pages/Gpb/Create.vue';
import SdizGpbDetail from '@/views/Sdiz/components/Pages/Gpb/Detail.vue';
import LotGpbCreate from '@/views/Lot/components/Pages/Gpb/Create.vue';
import LotGpbList from '@/views/Lot/components/Pages/Gpb/List.vue';
import LotGpbDetail from '@/views/Lot/components/Pages/Gpb/Detail.vue';
import LotElevatorCreate from '@/views/Lot/components/Pages/Elevator/Create.vue';
import LotDefaultList from '@/views/Lot/components/Pages/Default/List.vue';
import LotDefaultCreate from '@/views/Lot/components/Pages/Default/Create.vue';
import LotDefaultDetail from '@/views/Lot/components/Pages/Default/Detail.vue';
import LotElevatorDetail from '@/views/Lot/components/Pages/Elevator/Detail.vue';
import SdizSearchList from '@/views/SearchSdiz/List.vue';
import StateAuthority from '@/views/StateAuthority/StateAuthority.vue';
import StateAuthorityCard from '@/views/StateAuthority/components/StateAuthorityCard.vue';
import SdizAgentCreate from '@/views/SdizAgent/components/Create.vue';
import SdizAgent from '@/views/SdizAgent/SdizAgent.vue';
import SdizAgentView from '@/views/SdizAgent/components/View.vue';
import LotOgvDetail from '@/views/OgvRegistry/Lot/Detail.vue';
import LotOgvList from '@/views/OgvRegistry/Lot/List.vue';
import SdizOgvList from '@/views/OgvRegistry/Sdiz/List.vue';
import SdizOgvDetail from '@/views/OgvRegistry/Sdiz/Detail.vue';
import LotOgvElevatorList from '@/views/OgvRegistry/LotElevator/List.vue';
import LotOgvElevatorDetail from '@/views/OgvRegistry/LotElevator/Detail.vue';
import LotOgvGpbList from '@/views/OgvRegistry/LotGpb/List.vue';
import LotOgvGpbDetail from '@/views/OgvRegistry/LotGpb/Detail.vue';
import SdizOgvGpbList from '@/views/OgvRegistry/SdizGpb/List.vue';
import SdizOgvGpbDetail from '@/views/OgvRegistry/SdizGpb/Detail.vue';
import SdizAgentList from '@/views/SdizAgent/Pages/List.vue';
import SdizAgentOgvList from '@/views/OgvRegistry/SdizAgent/List.vue';
import SdizAgentOgvView from '@/views/OgvRegistry/SdizAgent/View.vue';
import Versions from '@/views/Versions/Versions.vue';
import SdizElevatorDetail from '@/views/Sdiz/components/Pages/Elevator/Detail.vue';
import SubmitedByManufacturersDetail from '@/views/Gosmonitoring/components/Register/SubmittedByManufacturers/Detail.vue';
import ConductedResearchRegisterDetail from '@/views/Gosmonitoring/components/Register/ConductedResearchManufacturers/Detail.vue';
import GpbOutDefaultList from '@/views/GpbOut/Pages/Default/List.vue';
import GpbOut from '@/views/GpbOut/GpbOut.vue';
import GpbOutCreate from '@/views/GpbOut/components/Create.vue';
import GpbOutOgvList from '@/views/OgvRegistry/GpbOut/List.vue';
import GpbOutOgvDetail from '@/views/OgvRegistry/GpbOut/Detail.vue';
import GpbOutDetailDefault from '@/views/GpbOut/Pages/Default/Detail.vue';
import Rshn from '@/views/rshn/Rshn.vue';
import RshnWithDrawalList from '@/views/rshn/Pages/Withdrawal/List.vue';
import RshnWithdrawalCreate from '@/views/rshn/Pages/Withdrawal/Create.vue';
import RshnWithdrawalDetail from '@/views/rshn/Pages/Withdrawal/Detail.vue';
import RshnPrescriptionList from '@/views/rshn/Pages/Prescription/List.vue';
import RshnPrescriptionCreate from '@/views/rshn/Pages/Prescription/Create.vue';
import RshnPrescriptionDetail from '@/views/rshn/Pages/Prescription/Detail.vue';
import RshnExpertiseList from '@/views/rshn/Pages/Expertise/List.vue';
import RshnExpertiseCreate from '@/views/rshn/Pages/Expertise/Create.vue';
import RshnExpertiseDetail from '@/views/rshn/Pages/Expertise/Detail.vue';
import RegionalGovernment from '@/views/RegionalGovernment/RegionalGovernment.vue';
import LotRouApk from '@/views/RegionalGovernment/Pages/Lot.vue';
import SdizRouApk from '@/views/RegionalGovernment/Pages/Sdiz.vue';
import ImplementationRouApk from '@/views/RegionalGovernment/Pages/Implementation.vue';

export default [
  { path: '/', redirect: '/home' },
  {
    path: '/loginForm',
    name: 'login',
    component: EsiaLogin,
    meta: {
      auth: false,
      breadcrumb: [{ name: 'Авторизация ЕСИА' }],
    },
  },
  {
    path: '/login',
    name: 'esia',
    component: LoginRouter,
    meta: {
      auth: false,
      breadcrumb: [{ name: 'Вход' }],
    },
  },
  {
    path: '/passwordRecovery',
    name: 'passwordRecovery',
    component: PasswordRecovery,
    meta: {
      auth: false,
      breadcrumb: [{ name: 'Восстановления пароля' }],
    },
  },
  {
    path: '/ogv',
    name: 'ogv_lot',
    component: Lot,
    meta: {
      breadcrumb: [{ name: 'Реестры партий и СДИЗ' }],
    },
    children: [
      {
        path: '/ogv/list',
        name: 'ogv_list',
        component: LotOgvList,
        meta: {
          breadcrumb: [{ name: 'Реестр партии зерна' }],
        },
      },
      {
        path: '/ogv/detail/:id',
        name: 'ogv_lot_detail',
        component: LotOgvDetail,
      },
      {
        path: '/ogv/list-elevator',
        name: 'ogv_list_elevator',
        component: LotOgvElevatorList,
        meta: {
          breadcrumb: [{ name: 'Реестр партии зерна на хранение' }],
        },
      },
      {
        path: '/ogv/detail-elevator/:id',
        name: 'ogv_lot_detail_elevator',
        component: LotOgvElevatorDetail,
      },
      {
        path: '/ogv/list-gpb',
        name: 'ogv_list_gpb',
        component: LotOgvGpbList,
        meta: {
          breadcrumb: [{ name: 'Реестр партии продуктов переработки' }],
        },
      },
      {
        path: '/ogv/detail-gpb/:id',
        name: 'ogv_lot_detail_gpb',
        component: LotOgvGpbDetail,
      },
    ],
  },
  {
    path: '/ogv',
    name: 'ogv_sdiz',
    component: Sdiz,
    meta: {
      breadcrumb: [{ name: 'Реестры партий и СДИЗ' }],
    },
    children: [
      {
        path: '/ogv/list-sdiz',
        name: 'ogv_sdizs_list',
        component: SdizOgvList,
        meta: {
          breadcrumb: [{ name: 'Реестр СДИЗ' }],
        },
      },
      {
        path: '/ogv/detail-sdiz/:id',
        name: 'ogv_sdizs_detail',
        component: SdizOgvDetail,
      },
      {
        path: '/ogv/list-sdiz-gpb',
        name: 'ogv_sdizs_list_gpb',
        component: SdizOgvGpbList,
        meta: {
          breadcrumb: [{ name: 'Реестр СДИЗ на продукты переработки' }],
        },
      },
      {
        path: '/ogv/list-sdiz-gpb/:id',
        name: 'ogv_sdizs_detail_gpb',
        component: SdizOgvGpbDetail,
      },
    ],
  },
  {
    path: '/ogv',
    name: 'ogv_gpb_out',
    component: GpbOut,
    meta: {
      breadcrumb: [{ name: 'Реестр производств, не подлежащих учету в системе' }],
    },
    children: [
      {
        path: '/ogv/list-gpb-out',
        name: 'ogv_list_gpb_out',
        component: GpbOutOgvList,
        meta: {
          breadcrumb: [{ name: 'Реестр производств, не подлежащих учету в системе' }],
        },
      },
      {
        path: '/ogv/show-gpb-out/:id',
        name: 'ogv_show_gpb_out',
        component: GpbOutOgvDetail,
      },
    ],
  },
  {
    path: '/ogv',
    name: 'ogv_agent',
    component: SdizAgent,
    meta: {
      breadcrumb: [{ name: 'Реестры партий и СДИЗ' }],
    },
    children: [
      {
        path: '/ogv/list-agent-sdiz',
        name: 'ogv_agent_list_sdiz',
        component: SdizAgentOgvList,
        meta: {
          breadcrumb: [{ name: 'Реестр сведений предоставляемых агентом' }],
        },
      },
      {
        path: '/ogv/viewAgentSdiz/:id',
        name: 'ogv_agent_view_sdiz',
        component: SdizAgentOgvView,
      },
    ],
  },
  {
    path: '/lots',
    name: 'lot',
    component: Lot,
    meta: {
      breadcrumb: [{ name: 'Управление партиями зерна' }],
    },
    children: [
      {
        path: '/lots/list',
        name: 'lot_list',
        component: LotDefaultList,
        meta: {
          breadcrumb: [{ name: 'Реестр партии зерна' }],
        },
      },
      {
        path: '/lots/createFromField',
        name: 'lot_create_from_field',
        component: LotDefaultCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии зерна по результатам государственного мониторинга' }],
        },
      },
      {
        path: '/lots/createFromAnotherBatch',
        name: 'lot_create_from_another_batch',
        component: LotDefaultCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии зерна из других партий' }],
        },
      },
      {
        path: '/lots/createFromResidues',
        name: 'lot_create_from_residues',
        component: LotDefaultCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии зерна из остатков' }],
        },
      },
      {
        path: '/lots/createFromSdiz',
        name: 'lot_create_from_sdiz',
        component: LotDefaultCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии зерна на основании СДИЗ на бумажном носителе' }],
        },
      },
      {
        path: '/lots/createFromImported',
        name: 'lot_create_from_imported',
        component: LotDefaultCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии зерна при ввозе' }],
        },
      },

      {
        path: '/lots/:id',
        name: 'lot_detail',
        component: LotDefaultDetail,
      },
    ],
  },
  {
    path: '/lots',
    name: 'lot-elevator',
    component: Lot,
    meta: {
      breadcrumb: [{ name: 'Управление партиями зерна на хранении' }],
    },
    children: [
      {
        path: '/lots/elevator/list',
        name: 'lot_elevator_list',
        component: LotElevatorList,
        meta: {
          breadcrumb: [{ name: 'Реестр партии зерна при хранении' }],
        },
      },
      {
        path: '/lots/elevator/createFromField',
        name: 'lot_elevator_create_from_field',
        component: LotElevatorCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии зерна по результатам государственного мониторинга' }],
        },
      },
      {
        path: '/lots/elevator/createFromAnotherBatch',
        name: 'lot_elevator_create_from_another_batch',
        component: LotElevatorCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии зерна из других партий' }],
        },
      },
      {
        path: '/lots/elevator/createFromResidues',
        name: 'lot_elevator_create_from_residues',
        component: LotElevatorCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии зерна из остатков' }],
        },
      },
      {
        path: '/lots/elevator/createFromSdiz',
        name: 'lot_elevator_create_from_sdiz',
        component: LotElevatorCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии зерна на основании СДИЗ на бумажном носителе' }],
        },
      },

      {
        path: '/lots-elevator/:id',
        name: 'lot_elevator_detail',
        component: LotElevatorDetail,
      },
    ],
  },
  {
    path: '/lots',
    name: 'lot-gpb',
    component: Lot,
    meta: {
      breadcrumb: [{ name: 'Управление партиями продуктов переработки зерна' }],
    },
    children: [
      {
        path: '/lots/gpb/list',
        name: 'lots_gpb_list',
        component: LotGpbList,
        meta: {
          breadcrumb: [{ name: 'Реестр партии продуктов переработки зерна' }],
        },
      },
      {
        path: '/lots/gpb/createFromInProduct',
        name: 'lots_gpb_create_from_in_product',
        component: LotGpbCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии продуктов переработки зерна при производстве' }],
        },
      },
      {
        path: '/lots/gpb/createFromAnotherBatch',
        name: 'lots_gpb_create_from_another_batch',
        component: LotGpbCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии продуктов переработки зерна из других партий' }],
        },
      },
      {
        path: '/lots/gpb/createFromResidues',
        name: 'lots_gpb_create_from_residues',
        component: LotGpbCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии продуктов переработки зерна из остатков' }],
        },
      },
      {
        path: '/lots/gpb/createFromSdiz',
        name: 'lots_gpb_create_from_sdiz',
        component: LotGpbCreate,
        meta: {
          breadcrumb: [
            { name: 'Формирование партии продуктов переработки зерна на основании СДИЗ на бумажном носителе' },
          ],
        },
      },
      {
        path: '/lots/gpb/createFromImported',
        name: 'lots_gpb_create_from_imported',
        component: LotGpbCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование партии продуктов переработки зерна при ввозе' }],
        },
      },

      {
        path: '/lots/gpb/:id',
        name: 'lots_gpb_detail',
        component: LotGpbDetail,
      },
    ],
  },
  {
    path: '/rshn',
    name: 'rshn',
    component: Rshn,
    meta: {
      breadcrumb: [{ name: 'РСХН' }],
    },
    children: [
      {
        path: '/rshn/withdrawal/list',
        name: 'rshn_withdrawal_list',
        component: RshnWithDrawalList,
        meta: {
          breadcrumb: [{ name: 'Реестр со сведениями об изъятии' }],
        },
      },
      {
        path: '/rshn/withdrawal/create',
        name: 'rshn_withdrawal_create',
        component: RshnWithdrawalCreate,
        meta: {
          breadcrumb: [{ name: 'Создание сведений об изъятии' }],
        },
      },
      {
        path: '/rshn/withdrawal/:id',
        name: 'rshn_withdrawal_detail',
        component: RshnWithdrawalDetail,
        meta: {
          breadcrumb: [{ name: 'Просмотр сведений об изъятии' }],
        },
      },
      {
        path: '/rshn/prescription/list',
        name: 'rshn_prescription_list',
        component: RshnPrescriptionList,
        meta: {
          breadcrumb: [{ name: 'Реестр выданных предписаний' }],
        },
      },
      {
        path: '/rshn/prescription/create',
        name: 'rshn_prescription_create',
        component: RshnPrescriptionCreate,
        meta: {
          breadcrumb: [{ name: 'Создание сведений об предписании' }],
        },
      },
      {
        path: '/rshn/prescription/:id',
        name: 'rshn_prescription_detail',
        component: RshnPrescriptionDetail,
        meta: {
          breadcrumb: [{ name: 'Просмотр сведений об предписании' }],
        },
      },
      {
        path: '/rshn/expertise/list',
        name: 'rshn_expertise_list',
        component: RshnExpertiseList,
        meta: {
          breadcrumb: [{ name: 'Реестр экспертиз' }],
        },
      },
      {
        path: '/rshn/expertise/create',
        name: 'rshn_expertise_create',
        component: RshnExpertiseCreate,
        meta: {
          breadcrumb: [{ name: 'Создание сведений об экспертизе' }],
        },
      },
      {
        path: '/rshn/expertise/:id',
        name: 'rshn_expertise_detail',
        component: RshnExpertiseDetail,
        meta: {
          breadcrumb: [{ name: 'Просмотр сведений об экспертизе' }],
        },
      },
    ],
  },
  {
    path: '/gpbo',
    name: 'gpb-out',
    component: GpbOut,
    meta: {
      breadcrumb: [{ name: 'Управление производствами, не подлежащими учету в системе' }],
    },
    children: [
      {
        path: '/gpbo/gpb-out/list',
        name: 'gpbo_gpb_out_list',
        component: GpbOutDefaultList,
        meta: {
          breadcrumb: [{ name: 'Реестр производств, не подлежащих учету в системе' }],
        },
      },
      {
        path: '/gpbo/gpb-out/create',
        name: 'gpbo_gpb_out_create',
        component: GpbOutCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование производства продукции, не подлежащей учету' }],
        },
      },
      {
        path: '/gpbo/gpb-out/:id',
        name: 'gpbo_gpb_out_detail',
        component: GpbOutDetailDefault,
        meta: {
          breadcrumb: [{ name: 'Производство продукции, не подлежащей учету' }],
        },
      },
    ],
  },
  {
    path: '/reserves',
    name: 'reserves',
    component: Reserves,
    meta: {
      breadcrumb: [{ name: 'Реестр номеров' }],
    },
    children: [
      {
        path: '/reserves/number/sdiz',
        name: 'reserves_number_sdiz',
        component: ReservesNumberSdiz,
        meta: {
          breadcrumb: [{ name: 'Реестр выданных номеров СДИЗ' }],
        },
      },
      {
        path: '/reserves/number/lots',
        name: 'reserves_number_lots',
        component: ReservesNumberLot,
        meta: {
          breadcrumb: [{ name: 'Реестр выданных номеров партий зерна' }],
        },
      },
      {
        path: '/reserves/number/products',
        name: 'reserves_number_products',
        component: ReservesNumberProducts,
        meta: {
          breadcrumb: [{ name: 'Реестр выданных номеров партий продуктов переработки зерна' }],
        },
      },
    ],
  },
  {
    path: '/gosmonitoring',
    name: 'gosmonitoring',
    component: Gosmonitoring,
    meta: {
      breadcrumb: [{ name: 'Госмониторинг' }],
    },
    children: [
      {
        path: '/gosmonitoring/register/submitted-by-manufacturers',
        name: 'gosmonitoring_register_submitted_by_manufacturers',
        component: SubmittedByManufacturersList,
        meta: {
          breadcrumb: [{ name: 'Реестр поданных сведений товаропроизводителями' }],
        },
      },
      {
        path: '/gosmonitoring/register/submitted-by-manufacturers/detail/:id',
        name: 'gosmonitoring_register_submitted-by-manufacturers_detail',
        component: SubmitedByManufacturersDetail,
      },
      {
        path: '/gosmonitoring/register/conducted-research-manufacturers',
        name: 'gosmonitoring_register_conducted_research_manufacturers',
        component: ConductedResearchManufacturersList,
      },
      {
        path: '/gosmonitoring/register/conducted-research-manufacturers/detail/:id',
        name: 'gosmonitoring_register_conducted_research_manufacturers_detail',
        component: ConductedResearchRegisterDetail,
      },

      {
        path: '/gosmonitoring/register/implementation',
        name: 'gosmonitoring_register_implementation',
        component: ImplementationList,
      },
      {
        path: '/gosmonitoring/register/implementation/create',
        name: 'gosmonitoring_register_implementation_create',
        component: ImplementationCreate,
      },
      {
        path: '/gosmonitoring/register/implementation/update/:id',
        name: 'gosmonitoring_register_implementation_edit',
        component: ImplementationUpdate,
      },
      {
        path: '/gosmonitoring/register/implementation/detail/:id',
        name: 'gosmonitoring_register_implementation_detail',
        component: ImplementationDetail,
      },

      {
        path: '/gosmonitoring/research-register',
        name: 'gosmonitoring_research_register',
        component: ResearchRegisterList,
        meta: {
          breadcrumb: [{ name: 'Реестр проведенных исследований' }],
        },
      },
      {
        path: '/gosmonitoring/research-register/create',
        name: 'gosmonitoring_research_register_create',
        component: ResearchRegisterCreate,
      },
      {
        path: '/gosmonitoring/research-register/update/:id',
        name: 'gosmonitoring_research_register_edit',
        component: ResearchRegisterEdit,
      },
      {
        path: '/gosmonitoring/research-register/detail/:id',
        name: 'gosmonitoring_research_register_detail',
        component: ResearchRegisterDetail,
      },
    ],
  },
  {
    path: '/sdizs-search',
    name: 'search-sdiz',
    component: SdizSearchList,
    meta: {
      breadcrumb: [{ name: 'Поиск СДИЗ' }],
    },
  },
  {
    path: '/sdizs/agent',
    name: 'search',
    component: SdizAgent,
    meta: {
      breadcrumb: [{ name: 'Сведения предоставляемые агентом' }],
    },
    children: [
      {
        path: '/sdizs/agent/list',
        name: 'sdiz_agent_list',
        component: SdizAgentList,
        meta: {
          breadcrumb: [{ name: 'Реестр сведений предоставляемых агентом' }],
        },
      },
      {
        path: '/sdizs/agent/create',
        name: 'sdiz_agent_create',
        component: SdizAgentCreate,
        meta: {
          breadcrumb: [{ name: 'Формирование сведений предоставляемых агентом' }],
        },
      },
      {
        path: '/sdizs/agent/:id',
        name: 'sdiz_agent_detail',
        component: SdizAgentView,
      },
    ],
  },
  {
    path: '/:directory(ogv|sdizs)/declaration',
    name: 'declaration-register',
    component: () => import('@/views/Sdiz/DeclarationPage.vue'),
    meta: {
      breadcrumb: [{ name: 'Реестр деклараций ФТС' }],
    },
  },
  {
    path: '/sdizs',
    name: 'sdiz',
    component: Sdiz,
    meta: {
      breadcrumb: [{ name: 'Управление СДИЗ' }],
    },
    children: [
      {
        path: '/sdizs/list',
        name: 'sdiz_list',
        component: SdizList,
        meta: {
          breadcrumb: [{ name: 'Реестр СДИЗ' }],
        },
      },
      {
        path: '/sdizs/create',
        name: 'sdiz_create',
        component: SdizCreate,
        meta: {
          breadcrumb: [{ name: 'Оформление СДИЗ' }],
        },
      },
      {
        path: '/sdizs/show/:id',
        name: 'sdiz_detail',
        component: SdizDetail,
      },
    ],
  },
  {
    path: '/sdizs-gpb',
    name: 'sdiz_gpb',
    component: Sdiz,
    meta: {
      breadcrumb: [{ name: 'Управление СДИЗ продуктов переработки' }],
    },
    children: [
      {
        path: '/sdizs-gpb/show/:id',
        name: 'sdiz_gpb_detail',
        component: SdizGpbDetail,
      },
      {
        path: '/sdizs-gpb/list',
        name: 'sdiz_gpb_list',
        component: SdizGpbList,
        meta: {
          breadcrumb: [{ name: 'Реестр СДИЗ продуктов переработки' }],
        },
      },
      {
        path: '/sdizs-gpb/create',
        name: 'sdiz_gpb_create',
        component: SdizGpbCreate,
        meta: {
          breadcrumb: [{ name: 'Оформление СДИЗ продуктов переработки' }],
        },
      },
    ],
  },

  {
    path: '/sdizs-elevator',
    name: 'sdiz_elevator',
    component: Sdiz,
    meta: {
      breadcrumb: [{ name: 'Управление СДИЗ при хранении' }],
    },
    children: [
      {
        path: '/sdizs-elevator/list',
        name: 'sdiz_elevator_list',
        component: SdizElevatorList,
        meta: {
          breadcrumb: [{ name: 'Реестр СДИЗ при хранении' }],
        },
      },
      {
        path: '/sdizs-elevator/create',
        name: 'sdiz_elevator_create',
        component: SdizElevatorCreate,
        meta: {
          breadcrumb: [{ name: 'Оформление СДИЗ при хранении' }],
        },
      },
      {
        path: '/sdizs-elevator/show/:id',
        name: 'sdiz_elevator_detail',
        component: SdizElevatorDetail,
      },
    ],
  },

  {
    path: '/nsi',
    name: 'nsi',
    component: NSI,
    meta: {
      breadcrumb: [{ name: 'Справочники' }],
    },
    children: [
      {
        path: '/nsi/:mask',
        name: 'nsi.list',
        component: NsiList,
        meta: {
          breadcrumb: [{ name: '' }],
        },
      },
      {
        path: '/nsi/:mask/view/:id',
        name: 'nsi.card.view',
        component: NsiCard,
      },
      {
        path: '/nsi/:mask/edit/:id',
        name: 'nsi.card.edit',
        component: NsiCard,
      },
      {
        path: '/nsi/:mask/create',
        name: 'nsi.card.create',
        component: NsiCard,
      },
    ],
  },
  {
    path: '/manufacturers',
    name: 'ManufacturersRegisterPage',
    component: () =>
      import(/* webpackChunkName: "manufacturers" */ '@/views/Manufacturers/ManufacturersRegisterPage.vue'),
    meta: {
      breadcrumb: [{ name: 'Реестр товаропроизводителей' }],
    },
  },
  {
    path: '/manufacturers/edit/:id',
    name: 'ManufacturersCreate',
    component: () => import(/* webpackChunkName: "manufacturersCreate" */ '@/components/SubjectForm/SubjectForm.vue'),
    meta: {
      breadcrumb: [{ name: 'Редактирование организации' }],
      backRoute: '/manufacturers',
    },
  },
  {
    path: '/manufacturers/create',
    name: 'SubjectForm',
    component: () => import(/* webpackChunkName: "manufacturersCreate" */ '@/components/SubjectForm/SubjectForm.vue'),
    meta: {
      breadcrumb: [{ name: 'Добавление организации' }],
      backRoute: '/manufacturers',
    },
  },

  {
    path: '/manufacturers/edit',
    name: 'OrganizationEditCard',
    component: () => import(/* webpackChunkName: "manufacturers" */ '@/components/Organization/edit/EditCard.vue'),
    meta: {
      breadcrumb: [{ name: 'Добавление товаропроизводиля' }],
      backRoute: '/manufacturers',
    },
  },
  {
    path: '/manufacturers/:id',
    name: 'OrganizationViewCard',
    component: () => import(/* webpackChunkName: "manufacturers" */ '@/components/Organization/view/ViewCard.vue'),
    meta: {
      breadcrumb: [{ name: 'Карточка товаропроизводителя' }],
      backRoute: '/manufacturers',
    },
  },

  {
    path: '/numbers-for-manufacturers',
    name: 'numbersForManufacturers',
    component: NumbersForManufacturers,
    meta: {
      breadcrumb: [{ name: 'Реестр мест формирования партии зерна' }],
    },
  },
  {
    path: '/laboratories',
    name: 'laboratories',
    component: Laboratories,
    meta: {
      breadcrumb: [{ name: 'Реестр лабораторий' }],
    },
  },
  {
    path: '/laboratories/create',
    name: 'laboratoriesCreate',
    component: () => import(/* webpackChunkName: "laboratories" */ '@/components/SubjectForm/SubjectForm.vue'),
    meta: {
      breadcrumb: [{ name: 'Добавление организации' }],
      backRoute: '/laboratories',
    },
  },
  {
    path: '/laboratories/edit/:id',
    name: 'laboratoriesEdit',
    component: () => import(/* webpackChunkName: "laboratories" */ '@/components/SubjectForm/SubjectForm.vue'),
    meta: {
      breadcrumb: [{ name: 'Редактирование организации' }],
      backRoute: '/laboratories',
    },
  },
  {
    path: '/laboratories/:id',
    name: 'LaboratoriesViewCard',
    component: () => import(/* webpackChunkName: "laboratories" */ '@/components/Laboratory/view/ViewCard.vue'),
    meta: {
      breadcrumb: [{ name: 'Карточка лабоработории' }],
      backRoute: '/laboratories',
    },
  },
  {
    path: '/register-organizations',
    name: 'organization',
    component: () => import(/* webpackChunkName: "organizations"*/ '@/views/RegisterOrganizations/Organizations.vue'),
    meta: {
      breadcrumb: [{ name: 'Реестр организаций' }],
    },
  },
  {
    path: '/organization/certificates',
    name: 'organization-certificates',
    component: () =>
      import(
        /* webpackChunkName: "certificates" */ '@/views/MyOrganization/Certificate/CertificateOrganizationPage.vue'
      ),
    meta: {
      type: 'organization',
      breadcrumb: [{ name: 'Сертификаты безопасности организации' }],
    },
  },
  {
    path: '/requests',
    name: 'requestsList',
    component: RequestsList,
    children: [
      {
        path: '/requests/:id/:type',
        name: 'card-requests',
        component: RequestCard,
        meta: {
          breadcrumb: [{ name: 'Заявление' }],
          backRoute: '/requests',
        },
      },
      {
        path: '/requests/create',
        name: 'card-requests',
        component: RequestCard,
        meta: {
          breadcrumb: [{ name: 'Новое заявление' }],
          backRoute: '/requests',
        },
      },
    ],
    meta: {
      breadcrumb: [{ name: 'Заявления' }],
    },
  },
  {
    path: '/tasks-for-approval',
    name: 'approval',
    component: Approval,
    meta: {
      breadcrumb: [{ name: 'Рассмотрение' }],
    },
    children: [
      {
        path: '/tasks-for-approval/:id/:type',
        name: 'card-requests-tasks',
        component: RequestCard,
        meta: {
          breadcrumb: [{ name: 'Задача на согласование' }],
          backRoute: '/tasks-for-approval',
        },
      },
    ],
  },
  {
    path: '/approval-templates',
    name: 'templates',
    component: Templates,
    meta: {
      breadcrumb: [{ name: 'Шаблоны рассмотрения заявлений' }],
    },
  },
  {
    path: '/home',
    name: 'Home',
    component: Home,
    meta: {
      breadcrumb: [{ name: 'Главная' }],
    },
  },
  {
    path: '/contracts',
    name: 'contracts',
    component: Agents,
    meta: {
      breadcrumb: [{ name: 'Государственные контракты с агентами' }],
    },
  },
  // =================== Администрирование ======================
  {
    path: '/staff',
    name: 'staff',
    redirect: '/administration/users',
  },
  {
    path: '/activityLog',
    name: 'activityLog',
    redirect: '/administration/activity-log',
  },
  {
    path: '/userRoles',
    name: 'userRoles',
    redirect: '/administration/roles',
  },
  {
    path: '/administration/users',
    name: 'users',
    component: () =>
      import(/* webpackChunkName: "administration" */ '@/views/Administration/Staff/components/StaffList.vue'),
    meta: {
      breadcrumb: [{ name: 'Реестр пользователей' }],
    },
  },
  {
    path: '/administration/activity-log',
    name: 'activityLog',
    component: () =>
      import(/* webpackChunkName: "administration" */ '@/views/Administration/LoggingLog/components/LoggingList.vue'),
    meta: {
      breadcrumb: [{ name: 'Журнал действий пользователя' }],
    },
  },
  {
    path: '/administration/roles',
    name: 'userRoles',
    component: () =>
      import(/* webpackChunkName: "administration" */ '@/views/Administration/UserRoles/components/UserRolesList.vue'),
    meta: {
      breadcrumb: [{ name: 'Реестр ролей' }],
    },
  },
  {
    path: '/administration/approval-request-log',
    name: 'approvalRequestLog',
    component: () =>
      import(
        /* webpackChunkName: "administration" */ '@/views/Administration/ApprovalRequestLog/ApprovalRequestLogPage.vue'
      ),
    meta: {
      breadcrumb: [{ name: 'Журнал согласования заявлений' }],
    },
  },
  {
    path: '/administration/import',
    name: 'import',
    component: () => import(/* webpackChunkName: "administration" */ '@/views/Administration/ImportLogPage.vue'),
    meta: {
      breadcrumb: [{ name: 'Реестр загрузок' }],
    },
  },
  {
    path: '/administration/system-certificates',
    name: 'system-certificates',
    component: () =>
      import(/* webpackChunkName: "certificates" */ '@/views/Administration/CertificateManagementPage.vue'),
    meta: {
      type: 'system',
      breadcrumb: [{ name: 'Управление системными сертификатами' }],
    },
  },
  {
    path: '/administration/interaction-log',
    name: 'interaction-log',
    component: () => import(/* webpackChunkName: "administration" */ '@/views/Administration/InteractionLogPage.vue'),
    meta: {
      breadcrumb: [{ name: 'Журнал информационного взаимодействия' }],
    },
  },
  {
    path: '/subjects',
    name: 'subjects',
    component: () => import(/* webpackChunkName: "administration" */ '@/views/Administration/Subject/Subject.vue'),
    meta: {
      breadcrumb: [{ name: 'Реестр всех организаций' }],
    },
  },
  {
    path: '/subjects/create',
    name: 'subjectsCreate',
    component: () => import(/* webpackChunkName: "administration" */ '@/components/SubjectForm/SubjectForm.vue'),
    meta: {
      breadcrumb: [{ name: 'Добавление организации' }],
      backRoute: '/subjects',
    },
  },
  {
    path: '/subjects/edit/:id',
    name: 'subjectsEdit',
    component: () => import(/* webpackChunkName: "administration" */ '@/components/SubjectForm/SubjectForm.vue'),
    meta: {
      breadcrumb: [{ name: 'Редактирование организации' }],
      backRoute: '/subjects',
    },
  },
  {
    path: '/subjects/:id',
    name: 'subject_card',
    component: () => import(/* webpackChunkName: "administration" */ '@/components/Subjects/view/ViewCard.vue'),
    meta: {
      breadcrumb: [{ name: 'Карточка организации' }],
    },
  },
  {
    path: '/load-fias',
    name: 'monitoringLoadFias',
    component: () =>
      import(
        /* webpackChunkName: "administration" */ '@/views/Administration/MonitoringLoadFias/MonitoringLoadFias.vue'
      ),
    meta: {
      breadcrumb: [{ name: 'Мониторинг загрузки ФИАС' }],
    },
  },
  // {
  //   path: '/subjects/edit/:id?',
  //   name: 'subject_edit',
  //   component: () => import(/* webpackChunkName: "administration" */ '@/views/Administration/Subject/Subject.vue'),
  //   meta: {
  //     breadcrumb: [{ name: 'Редактирование организации' }],
  //   },
  // },
  // ===============================================================
  {
    path: '/stateAuthority',
    name: 'stateAuthority',
    component: StateAuthority,
    meta: {
      breadcrumb: [{ name: 'Органы государственной власти' }],
    },
  },
  {
    path: '/stateAuthority/create',
    name: 'stateAuthorityCreate',
    component: () => import(/* webpackChunkName: "stateAuthority" */ '@/components/SubjectForm/SubjectForm.vue'),
    meta: {
      breadcrumb: [{ name: 'Добавление организации' }],
      backRoute: '/stateAuthority',
    },
  },
  {
    path: '/stateAuthority/edit/:id',
    name: 'stateAuthorityEdit',
    component: () => import(/* webpackChunkName: "stateAuthority" */ '@/components/SubjectForm/SubjectForm.vue'),
    meta: {
      breadcrumb: [{ name: 'Добавление организации' }],
      backRoute: '/stateAuthority',
    },
  },
  {
    path: '/stateAuthority/:id',
    name: 'stateAuthorityViewCard',
    component: () => import(/* webpackChunkName: "stateAuthority" */ '@/components/StateAuthority/view/ViewCard.vue'),
    meta: {
      breadcrumb: [{ name: 'Карточка органа государственной власти' }],
      backRoute: '/stateAuthority',
    },
  },
  {
    path: '/stateAuthority/create',
    name: 'stateAuthority-card',
    component: StateAuthorityCard,
    meta: {
      breadcrumb: [{ name: 'Орган государственной власти' }],
    },
  },
  {
    path: '/versions',
    name: 'versions',
    component: Versions,
    meta: {
      breadcrumb: [{ name: 'Версии микросервисов' }],
    },
  },
  {
    path: '/user-files/:type(video|manual|documents|rutube)',
    component: () => import('@/views/Files/Files.vue'),
    meta: {
      breadcrumb: [
        { name: 'Видеоинструкции', type: 'video' },
        { name: 'Пользовательские инструкции', type: 'manual' },
        { name: 'Рабочая документация', type: 'documents' },
        { name: 'RUTUBE', type: 'rutube' },
      ],
    },
  },
  {
    path: '/notifications',
    name: 'notifications',
    component: () => import('@/views/Notifications/NotificationPage.vue'),
    meta: {
      breadcrumb: [{ name: 'Уведомления' }],
    },
  },
  {
    path: '/organization/information',
    name: 'organization-information',
    component: () => import(/* webpackChunkName: "certificates" */ '@/views/MyOrganization/MyOrganization.vue'),
    meta: {
      type: 'organization',
      breadcrumb: [{ name: 'Сведения об организации' }],
    },
  },
  {
    path: '/complaint',
    name: 'complaint-register',
    component: () => import('@/views/MyOrganization/Complaint/ComplaintRegister.vue'),
    meta: {
      breadcrumb: [{ name: 'Жалобы' }],
    },
  },
  {
    path: '/requestRegister',
    name: 'request-register',
    component: () => import('@/views/MyOrganization/RequestRegister/RequestRegister.vue'),
    meta: {
      breadcrumb: [{ name: 'Запросы' }],
    },
  },
  // =================== Роу АПК ======================
  {
    path: '/regional-government',
    name: 'regionalGovernment',
    component: RegionalGovernment,
    meta: {
      breadcrumb: [{ name: 'РОУ АПК' }],
    },
    children: [
      {
        path: '/regional-government/sdizs',
        name: 'sdizsForRegionalGovernment',
        component: SdizRouApk,
        meta: {
          breadcrumb: [{ name: 'Просмотр СДИЗ' }],
        },
      },
      {
        path: '/regional-government/lots',
        name: 'lotsForRegionalGovernment',
        component: LotRouApk,
        meta: {
          breadcrumb: [{ name: 'Просмотр партии зерна' }],
        },
      },
      {
        path: '/regional-government/implementations',
        name: 'implementationsForRegionalGovernment',
        component: ImplementationRouApk,
        meta: {
          breadcrumb: [{ name: 'Просмотр сведений о собранном урожае' }],
        },
      },
    ],
  },
];
