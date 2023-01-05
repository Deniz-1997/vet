import { Injectable } from '@angular/core';
import {VetLivestockRabbitsModel} from '../../../../../models/reports/livestock-report/rabbits/rabbits-settings.model';
import {VetLivestockHorsesCamelsModel} from '../../../../../models/reports/livestock-report/horses-camels/horses-camels-settings.model';
import {VetLivestockBirdsModel} from '../../../../../models/reports/livestock-report/birds/birds-settings.model';
import {VetCatsDogsSettingsModels} from '../../../../../models/reports/livestock-report/cats-dogs/cats-dogs-settings.model';
import {VetLivestockBeeModel} from '../../../../../models/reports/livestock-report/bee/bee-settings.model';
import {VetLivestockFishModel} from '../../../../../models/reports/livestock-report/fish/fish-settings.model';
import {VetLivestockCattleSettingsModel} from '../../../../../models/reports/livestock-report/cattle/cattle-settings.model';
import {VetLivestockPigsSettingsModel} from '../../../../../models/reports/livestock-report/pigs/pigs-settings.model';
import {VetLivestockSheepSettingsModel} from '../../../../../models/reports/livestock-report/sheep/sheep-settings.model';
import {VetLivestockGoatsSettingsModel} from '../../../../../models/reports/livestock-report/goats/goats-settings.model';
import {VetLivestockOthersSettingsModel} from '../../../../../models/reports/livestock-report/others/others-settings.model';
import {Vet3InformationSettingsModel} from '../../../../../models/reports/3-vet/information/information-settings.model';
import {Vet3CommonSettingsModel} from '../../../../../models/reports/3-vet/common/common-settings.model';
import {Vet3AquacultureSettingsModel} from '../../../../../models/reports/3-vet/aquaculture/aquaculture-settings.model';
import {VetReproductionCowsTotalModel} from '../../../../../models/reports/reproduction/cows-total/cows-total-settings.model';
import {VetReproductionAssumGestationModel} from '../../../../../models/reports/reproduction/assum-gestation/assum-gestation-settings.model';
import {VetReproductionCowsSpreadModel} from '../../../../../models/reports/reproduction/cows-spread/cows-spread-settings.model';
import {VetReproductionGestationLossModel} from '../../../../../models/reports/reproduction/gestation-loss/gestation-loss-settings.model';
import {VetReproductionCowsPeriodEndModel} from '../../../../../models/reports/reproduction/cows-period-end/cows-period-end-settings.model';
import {VetReproductionNeteliModel} from '../../../../../models/reports/reproduction/neteli/neteli-settings.model';
import {Vet1DiagnosticSettingsModel} from '../../../../../models/reports/1-vet-a/diagnostic/diagnostic-settings.model';
import {Vet1EventsSettingsModel} from '../../../../../models/reports/1-vet-a/events/events-settings.model';
import {Vet1OperationsSettingsModel} from '../../../../../models/reports/1-vet-a/operations/operations-settings.model';
import {Vet2ReportSettingsModel} from '../../../../../models/reports/2-vet/report/2-vet-report-settings.model';
import {Vet2NonContagiousAnimalsDiseasesSettingsModel} from '../../../../../models/reports/2-vet/non-contagious-animals-diseases/non-contagious-animals-diseases-settings.model';
import {Vet2DeathNonContagiousAnimalsDiseasesSettingsModel} from '../../../../../models/reports/2-vet/death-non-contagious-animals-diseases/death-non-contagious-animals-diseases-settings.model';
import {Vet2ComplexMedicalExaminationSettingsModel} from '../../../../../models/reports/2-vet/complex-medical-examination/complex-medical-examination-settings.model';
import {Vet2CattleBloodBiochemicalStudiesSettingsModel} from '../../../../../models/reports/2-vet/cattle-blood-biochemical-studies/cattle-blood-biochemical-studies-settings.model';
import {Vet2VitaminizedSettingsModel} from '../../../../../models/reports/2-vet/vitaminized/vitaminized-settings.model';
import {Vet2ObstetricMedicalExaminationSettingsModel} from '../../../../../models/reports/2-vet/obstetric-medical-examination/obstetric-medical-examination-settings.model';
import {Vet2AbortionsNumberSettingsModel} from '../../../../../models/reports/2-vet/abortions-number/abortions-number-settings.model';
import {Vet2MastitisInformationSettingsModel} from '../../../../../models/reports/2-vet/cow-mastitis-Information/cow-mastitis-Information-settings.model';
import {Vet2CalvesCultivationInIndividualDispensarySettingsModel} from '../../../../../models/reports/2-vet/calves-cultivation-in-individual-dispensary/calves-cultivation-in-individual-dispensary-settings.model';
import {Vet2AerosolTechnologyInfoSettingsModel} from '../../../../../models/reports/2-vet/aerosol-technology-info copy/aerosol-technology-info-settings.model';
import {Vet2LaserTechnologyInfoSettingsModel} from '../../../../../models/reports/2-vet/laser-technology-info/laser-technology-info-settings.model';
import {Vet2SummaryReportSettingsModel} from '../../../../../models/reports/2-vet/summary-report/summary-report-settings.model';
import {VetDisinfectantsSettingsModel} from '../../../../../models/reports/disinfectants/disinfectants-settings.model';
import {VetLeukemiaReportSettingsModel} from '../../../../../models/reports/report-leukemia/report/leukemia-report-settings.model';
import {VetPigsMoveReportSettingsModel} from '../../../../../models/reports/pigs-move/report/pigs-move-report-settings.model';
import {Vet1GReportSettingsModel} from '../../../../../models/reports/1-vet-g/report/1-vet-g-report-settings.model';

@Injectable()
export class ReportModelService {
  readonly titleHistoryReport = 'История изменений';
  readonly titleExplanatoryNote = 'Пояснительная записка';


  constructor() { }

  getReportModel(type: string): Object {
    let title: string;
    let tabs = [];
    switch (type) {
      case 'livestock-of-animals':
        title = 'Поголовье животных';
        tabs = [
          {
            name: 'Отчет', tables: [
              (new VetLivestockRabbitsModel()).getDataForTable(),
              (new VetLivestockHorsesCamelsModel()).getDataForTable(),
              (new VetLivestockBirdsModel()).getDataForTable(),
              (new VetCatsDogsSettingsModels()).getDataForTable(),
              (new VetLivestockBeeModel()).getDataForTable(),
              (new VetLivestockFishModel()).getDataForTable(),

              (new VetLivestockCattleSettingsModel()).getDataForTable(),
              (new VetLivestockPigsSettingsModel()).getDataForTable(),
              (new VetLivestockSheepSettingsModel()).getDataForTable(),
              (new VetLivestockGoatsSettingsModel()).getDataForTable(),
              (new VetLivestockOthersSettingsModel()).getDataForTable(),
            ], group: 'main'
          },
          {name: this.titleExplanatoryNote},
          {name: this.titleHistoryReport},
        ];
        break;
      case '3-vet':
        const inf = new Vet3InformationSettingsModel();
        const com = new Vet3CommonSettingsModel();
        const aqu = new Vet3AquacultureSettingsModel();
        title = '3-Вет: Болезни рыб';
        tabs = [
          {
            name: 'Общие положения', tables: [
              inf.getDataForTable(),
              com.getDataForTable()
            ], group: 'general_provisions'
          },
          {
            name: 'Сведения по учёту аквакультуры',
            tables: [
              aqu.getDataForTable()
            ],
            group: 'aquaculture_accounting_information'
          },
          {name: this.titleExplanatoryNote},
          {name: this.titleHistoryReport},
        ];
        break;
      case 'reproduction':
        title = 'Воспроизводство';
        tabs = [
          {
            name: 'Отчет', tables: [
              (new VetReproductionCowsTotalModel()).getDataForTable(),
              (new VetReproductionAssumGestationModel()).getDataForTable(),
              (new VetReproductionCowsSpreadModel()).getDataForTable(),
              (new VetReproductionGestationLossModel()).getDataForTable(),
              (new VetReproductionCowsPeriodEndModel()).getDataForTable(),
              (new VetReproductionNeteliModel()).getDataForTable(),
            ], group: 'main'
          },
          {name: this.titleExplanatoryNote},
          {name: this.titleHistoryReport},
        ];
        break;
      case '1-vet-a':
        title = '1-Вет А';
        tabs = [
          {
            name: 'Диагностические исследования', tables: [
              (new Vet1DiagnosticSettingsModel()).getDataForTable(),
            ], group: 'diagnistic'
          },
          {
            name: 'Мероприятия', tables: [
              (new Vet1EventsSettingsModel()).getDataForTable(),
            ], group: 'events'
          },
          {
            name: 'Работы', tables: [
              (new Vet1OperationsSettingsModel()).getDataForTable(),
            ], group: 'operations'
          },
          {name: this.titleExplanatoryNote},
          {name: this.titleHistoryReport},
        ];
        break;
      case '2-vet':
        title = '2-Вет';
        tabs = [
          {
            name: 'Отчет', tables: [
              (new Vet2ReportSettingsModel()).getDataForTable(),
            ], group: 'main'
          },
          {
            name: 'Детилизация', tables: [
              (new Vet2NonContagiousAnimalsDiseasesSettingsModel()).getDataForTable(),
              (new Vet2DeathNonContagiousAnimalsDiseasesSettingsModel()).getDataForTable(),
              (new Vet2ComplexMedicalExaminationSettingsModel()).getDataForTable(),
              (new Vet2CattleBloodBiochemicalStudiesSettingsModel()).getDataForTable(),
              (new Vet2VitaminizedSettingsModel()).getDataForTable(),
              (new Vet2ObstetricMedicalExaminationSettingsModel()).getDataForTable(),
              (new Vet2AbortionsNumberSettingsModel()).getDataForTable(),
              (new Vet2MastitisInformationSettingsModel()).getDataForTable(),
              (new Vet2CalvesCultivationInIndividualDispensarySettingsModel()).getDataForTable(),
              (new Vet2AerosolTechnologyInfoSettingsModel()).getDataForTable(),
              (new Vet2LaserTechnologyInfoSettingsModel()).getDataForTable(),
              (new Vet2SummaryReportSettingsModel()).getDataForTable(),
            ], group: 'details'
          },
          {name: this.titleExplanatoryNote},
          {name: this.titleHistoryReport},
        ];
        break;
      case 'disinfectants':
        title = 'Дез. средства';
        tabs = [
          {
            name: 'Отчет', tables: [
              (new VetDisinfectantsSettingsModel()).getDataForTable(),
            ], group: 'main'
          },
          {name: this.titleExplanatoryNote},
          {name: this.titleHistoryReport},
        ];
        break;
      case 'leukemia':
        title = 'Лейкоз';
        tabs = [
          {
            name: 'Отчет', tables: [
              (new VetLeukemiaReportSettingsModel()).getDataForTable(),
            ], group: 'main'
          },
          {name: this.titleExplanatoryNote},
          {name: this.titleHistoryReport},
        ];
        break;
      case 'pigs-move':
        title = 'Движение свиней';
        tabs = [
          {
            name: 'Отчет', tables: [
              (new VetPigsMoveReportSettingsModel()).getDataForTable(),
            ], group: 'main'
          },
          {name: this.titleExplanatoryNote},
          {name: this.titleHistoryReport},
        ];
        break;
      case '1-vet-g':
        title = '1-Вет Г';
        tabs = [
          {
            name: 'Отчет', tables: [
              (new Vet1GReportSettingsModel()).getDataForTable(),
            ], group: 'main'
          },
          {name: this.titleExplanatoryNote},
          {name: this.titleHistoryReport},
        ];
        break;
    }
    return {
      tabs: tabs,
      title: title,
    };
    }
}
