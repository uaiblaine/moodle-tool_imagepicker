<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Admin tool "Image Picker" - Language pack.
 *
 * @package    tool_imagepicker
 * @copyright  2026 Anderson Blaine
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['cropimage'] = 'Recortar imagem';
$string['demo_image'] = 'Imagem';
$string['demo_imagejpeg'] = 'Imagem (somente JPEG)';
$string['demo_imagenocrop'] = 'Imagem (sem recorte)';
$string['demo_imagepicker'] = 'Elemento seletor de imagens';
$string['demo_imagepicker_desc'] = 'Este é o elemento seletor de imagens dedicado que este plugin oferece. Ele sempre contém uma única imagem web, independentemente das opções com que foi criado, e oferece recorte livre.';
$string['demo_imagepickerjpeg'] = 'Elemento seletor de imagens restrito a imagens JPEG';
$string['demo_imagepickerjpeg_desc'] = 'Este é novamente o elemento seletor de imagens dedicado, criado com os tipos de arquivo aceitos .jpg, .jpeg, .jpe e .pdf. O elemento descarta os tipos de arquivo que não são imagens web, de modo que este campo aceita somente imagens JPEG. Uma imagem de outro tipo que esteja no campo mesmo assim (por exemplo, porque foi colocada ali antes de o campo ser tratado por este elemento) não recebe o botão de recorte, pois recortá-la resultaria em uma imagem PNG, que o campo não aceita.';
$string['demo_imagepickernocrop'] = 'Elemento seletor de imagens sem recorte';
$string['demo_imagepickernocrop_desc'] = 'Este é novamente o elemento seletor de imagens dedicado, criado com a opção enablecrop definida como false. Ele passa a ser um simples gerenciador de arquivos para uma única imagem web e não oferece o botão de recorte.';
$string['demo_imagepickerratio'] = 'Elemento seletor de imagens com proporção fixa';
$string['demo_imagepickerratio_desc'] = 'Este é novamente o elemento seletor de imagens dedicado, criado com a opção cropaspectratio definida como 16:9. A seleção de recorte mantém essa proporção.';
$string['demo_imagepickerratios'] = 'Elemento seletor de imagens com escolha de proporções';
$string['demo_imagepickerratios_desc'] = 'Este é novamente o elemento seletor de imagens dedicado, criado com a opção cropaspectratio definida como 16:9 e a opção cropaspectratios definida como livre, 1:1 e 16:9. O diálogo de recorte oferece essas três proporções e começa em 16:9, que marca como recomendada; a seleção de recorte mantém a proporção escolhida.';
$string['demo_imagepickersized'] = 'Elemento seletor de imagens com limite de tamanho';
$string['demo_imagepickersized_desc'] = 'Este é novamente o elemento seletor de imagens dedicado, criado com a opção cropmaxwidth definida como 200 e a opção cropmaxheight definida como 100. Uma imagem recortada é reduzida até caber em 200 x 100 pixels e nunca é ampliada. Uma imagem enviada e não recortada mantém o seu tamanho.';
$string['demo_imageratio'] = 'Imagem (16:9)';
$string['demo_imageratios'] = 'Imagem (escolha de proporções)';
$string['demo_imagesized'] = 'Imagem (no máximo 200 x 100 pixels)';
$string['demo_pageintro'] = 'Esta página mostra as formas como o seletor de imagens funciona em um formulário. As imagens são armazenadas quando o formulário é salvo, de modo que todo o ciclo de vida de uma imagem pode ser testado aqui: envie-a, recorte-a, salve o formulário e recorte-a novamente. Esta página serve apenas como demonstração das funcionalidades deste plugin; as imagens enviadas não são exibidas em nenhum outro lugar.';
$string['demo_pagetitle'] = 'Demonstração do seletor de imagens';
$string['error_cannotscaledown'] = 'A imagem recortada não pode ser reduzida o suficiente para caber no tamanho máximo de arquivo permitido aqui.';
$string['error_imageloadfailed'] = 'Não foi possível carregar a imagem do servidor, portanto ela não pode ser recortada. Tente novamente mais tarde.';
$string['error_imagetoolarge'] = 'A imagem recortada é maior que o tamanho máximo de arquivo permitido aqui ({$a}).';
$string['error_invalidcropregion'] = 'A área de recorte selecionada não está totalmente dentro da imagem. Selecione uma área dentro da imagem e tente novamente.';
$string['error_invalidimagecontent'] = 'O conteúdo da imagem é inválido.';
$string['error_invalidimagetype'] = 'O tipo de imagem não é permitido.';
$string['error_nodimensions'] = 'Esta imagem não pode ser recortada, pois não foi possível carregá-la em seu tamanho natural. É o caso de imagens vetoriais (SVG) que não especificam largura e altura.';
$string['error_noimagetocrop'] = 'Adicione uma imagem antes de recortá-la.';
$string['note_animated'] = 'Esta imagem é animada. Somente o primeiro quadro será mantido após o recorte.';
$string['note_formatchange'] = 'Após o recorte, esta imagem será salva como {$a}, pois o formato de arquivo atual não pode ser gravado pelo recortador de imagens.';
$string['note_maxheight'] = 'A imagem recortada é reduzida para no máximo {$a} pixels de altura, o tamanho em que ela é exibida.';
$string['note_maxsize'] = 'A imagem recortada é reduzida para caber em {$a->width} x {$a->height} pixels, o tamanho em que ela é exibida.';
$string['note_maxwidth'] = 'A imagem recortada é reduzida para no máximo {$a} pixels de largura, o tamanho em que ela é exibida.';
$string['note_originalpreserved'] = 'A imagem original é preservada no servidor para permitir alterações posteriores na área de recorte, se necessário. Se você quiser recortar conteúdo de imagem sensível, considere recortar a imagem localmente antes.';
$string['pluginname'] = 'Seletor de imagens';
$string['privacy:metadata'] = 'O seletor de imagens preserva a imagem original que dá suporte a uma imagem recortada. Esse original é armazenado no mesmo contexto da imagem a que dá suporte e é armazenado sem autor, pois pertence a essa imagem e não ao usuário que a recortou. Enquanto a imagem ainda não foi salva em nenhum lugar, esse é o contexto de usuário de quem a enviou, junto de seus arquivos de rascunho; o original acompanha a imagem assim que ela é salva e editada novamente. Assim que a imagem a que ele dá suporte é excluída, o original também é excluído. Portanto, o plugin não armazena dados pessoais.';
$string['ratio_free'] = 'Livre';
$string['ratio_label'] = 'Proporção';
$string['ratio_recommended'] = 'Recomendada';
$string['report_column_crop'] = 'Última região de recorte';
$string['report_column_cropped'] = 'Status da imagem recortada';
$string['report_column_id'] = 'ID';
$string['report_column_original'] = 'Status da imagem original';
$string['report_column_place'] = 'Local da imagem (componente / área de arquivos / id do item)';
$string['report_column_timemodified'] = 'Última modificação';
$string['report_contextmissing'] = 'Contexto {$a} (excluído)';
$string['report_cropped_inuse'] = 'Em uso';
$string['report_cropped_orphaned'] = 'Órfã';
$string['report_cropposition_percent'] = 'em {$a->x} % / {$a->y} % a partir do canto superior esquerdo';
$string['report_cropposition_px'] = 'em {$a->x} / {$a->y} px a partir do canto superior esquerdo';
$string['report_cropsize_percent'] = '{$a->width} % × {$a->height} % da imagem original';
$string['report_cropsize_px'] = '{$a->width} × {$a->height} px';
$string['report_delete'] = 'Excluir original';
$string['report_deleteall'] = 'Excluir todos os originais';
$string['report_deleteallconfirm'] = 'Deseja realmente excluir todos os originais preservados neste site? As imagens recortadas a que eles dão suporte não são afetadas, mas depois disso não poderão mais ser recortadas a partir de seus originais.';
$string['report_deleteconfirm'] = 'Deseja realmente excluir o original preservado com o ID {$a}? A imagem recortada a que ele dá suporte não é afetada, mas depois disso não poderá mais ser recortada a partir do original.';
$string['report_deleted'] = 'O original preservado foi excluído.';
$string['report_deletedall'] = '{$a} originais preservados foram excluídos.';
$string['report_disabled'] = 'A preservação de imagens originais está desativada no momento, portanto o recorte não preserva nada por enquanto. Os originais preservados anteriormente ainda aparecem aqui, mas não são mais usados para recorte: assim que a imagem a que um original dá suporte for recortada novamente ou excluída, o original se tornará órfão e será removido pela tarefa agendada. Se não quiser mantê-los até lá, você pode excluí-los aqui.';
$string['report_draftplace'] = 'Rascunho, ainda não salvo';
$string['report_enabled'] = 'A preservação de imagens originais está ativada no momento. Toda imagem recortada pela primeira vez é preservada como original e listada aqui.';
$string['report_nothingtodisplay'] = 'No momento, não há originais preservados neste site.';
$string['report_original_inplace'] = 'No lugar';
$string['report_original_missing'] = 'Ausente';
$string['report_pageintro'] = 'Este relatório lista as imagens originais (sem recorte) que o seletor de imagens preserva quando uma imagem é recortada. Um original se torna órfão assim que a imagem recortada a que ele dá suporte deixa de existir em qualquer lugar; os originais órfãos são removidos por uma tarefa agendada, mas também podem ser excluídos aqui imediatamente.';
$string['report_pagetitle'] = 'Originais preservados';
$string['report_view'] = 'Ver original';
$string['saving'] = 'Salvando a imagem recortada...';
$string['setting_croppingheading'] = 'Recorte';
$string['setting_demobutton'] = 'Ver página de demonstração';
$string['setting_demobutton_desc'] = 'Há uma página de demonstração em que você pode experimentar o seletor de imagens em suas diferentes formas. Ela está disponível somente para administradores.';
$string['setting_demoheading'] = 'Demonstração';
$string['setting_encodingquality'] = 'Qualidade da imagem após o recorte';
$string['setting_encodingquality_desc'] = 'A qualidade com que uma imagem recortada é codificada. Isso se aplica somente a formatos de imagem com perdas (JPEG e WebP); o PNG não tem perdas e ignora esta configuração. Uma qualidade maior significa um arquivo maior. O padrão de 92 % é um bom equilíbrio entre qualidade de imagem e tamanho de arquivo para a maioria das imagens.';
$string['setting_preserveoriginals'] = 'Preservar imagens originais';
$string['setting_preserveoriginals_desc'] = 'Se ativada, a imagem original (sem recorte) é preservada quando uma imagem é recortada. O recorte passa a partir sempre da imagem original, de modo que continua não destrutivo e o original nunca se perde. Isso exige armazenamento de arquivos adicional. Os originais preservados que não são mais usados são removidos automaticamente por uma tarefa agendada.<br /><br />Se desativada, o recorte parte da imagem armazenada no momento e a substitui. Os originais preservados enquanto a configuração estava ativada são mantidos, mas não são mais usados para recorte: assim que essa imagem for recortada novamente ou excluída, seu original deixa de ser usado e também é removido pela tarefa agendada. Os originais que você não quiser manter até lá podem ser excluídos imediatamente no relatório de originais preservados.';
$string['setting_reportbutton'] = 'Ver originais preservados';
$string['setting_reportbutton_desc'] = 'Há um relatório que lista todas as imagens originais que o plugin preserva no site, no qual elas podem ser visualizadas e excluídas. Ele está disponível somente para administradores.';
$string['setting_reportheading'] = 'Relatório';
$string['setting_sizelimitstrategy'] = 'Estratégia quando o limite de tamanho de arquivo é excedido';
$string['setting_sizelimitstrategy_desc'] = 'Uma imagem recortada pode exceder o tamanho máximo de arquivo que se aplica ao campo do formulário, mesmo cobrindo apenas uma parte da imagem original, porque o navegador precisa codificá-la novamente. Nesse caso, o usuário é solicitado a voltar e recortar uma área menor ou a deixar que a imagem recortada seja reduzida até caber. Esta configuração controla como ela é reduzida.<br />Reduzir o tamanho em pixels: a imagem recortada é reduzida passo a passo até caber. Sua qualidade permanece a configurada acima.<br />Reduzir primeiro a qualidade: a qualidade com que a imagem recortada é codificada é diminuída passo a passo (até 50 %) antes de o tamanho em pixels ser alterado. Somente se nem a menor qualidade bastar, a imagem também é reduzida. Isso mantém a resolução da imagem pelo maior tempo possível, ao custo de artefatos de compressão mais visíveis. Só funciona para formatos de imagem com perdas (JPEG e WebP); uma imagem PNG é sempre reduzida.';
$string['setting_sizelimitstrategy_lowerquality'] = 'Reduzir primeiro a qualidade';
$string['setting_sizelimitstrategy_scaledown'] = 'Reduzir o tamanho em pixels';
$string['sizelimit_back'] = 'Voltar ao recorte';
$string['sizelimit_choice_lowerquality'] = 'Você pode deixar que o Moodle reduza a qualidade da imagem recortada até que ela caiba no tamanho máximo de arquivo. A área recortada e sua resolução permanecem as mesmas; apenas a compressão aumenta. Se nem a menor qualidade bastar, a imagem também é reduzida. Como alternativa, você pode voltar e recortar uma área menor da imagem.';
$string['sizelimit_choice_scaledown'] = 'Você pode deixar que o Moodle reduza a imagem recortada até que ela caiba no tamanho máximo de arquivo. A área recortada permanece a mesma; apenas a resolução é reduzida. Como alternativa, você pode voltar e recortar uma área menor da imagem.';
$string['sizelimit_explanation'] = 'A imagem recortada tem um tamanho de arquivo de {$a->size}, mas o tamanho máximo de arquivo permitido aqui é {$a->limit}. Isso pode acontecer mesmo que a imagem recortada cubra apenas uma parte da imagem original: o recortador de imagens precisa codificar a imagem novamente, e isso não a torna necessariamente menor que o arquivo original.';
$string['sizelimit_lowerquality'] = 'Reduzir a qualidade da imagem';
$string['sizelimit_scaledown'] = 'Reduzir a imagem';
$string['sizelimit_title'] = 'Imagem recortada grande demais';
$string['task_cleanuporiginals'] = 'Limpar imagens originais órfãs';
