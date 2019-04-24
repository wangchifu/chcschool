@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '教師差假')

@section('content')
    <TABLE width='100%'  border='0' align='center' style="border:0px;">
        <TR><TD colspan=3 align='center' style="border:0px;"><b style="font-size:16pt;">彰化縣和美鎮和東國民小學出差請示單暨旅費報告表</b></TD></TR>
        <TR><TD width=40% style="border:0px;">
                <TABLE  align='left'>
                    <TR><TD>憑證編號</TD><TD colspan=3 align='center'>預算科目</TD></TR>
                    <TR><TD>&nbsp;</TD><TD>　　　　&nbsp;</TD><TD>　　　　&nbsp;</TD><TD>　　　　&nbsp;</TD></TR>
                </TABLE>
            </TD>
            <TD style="border:0px;" width=30%>&nbsp;</TD>
            <TD  width=30% style="border:0px;">
                <div style="text-align: right;font-size:10pt;">製表日期:2019.04.24</div>
            </TD>
        </TR></TABLE>

    <TABLE width='100%' border='0' align='center'>
        <TR align='center'>
            <TD width=15%><b>姓  名</b></TD>
            <TD width=18%>王麒富</TD>
            <TD width=15%><b>職 稱</b></TD>
            <TD width=18%>資訊組長 </TD>
            <TD width=15%><b>職 等</b></TD>
            <TD width=19%></TD>
        </TR>
        <TR align='center'>
            <TD><b>出差事由</b></TD>
            <TD align=left colspan=2>&nbsp;&nbsp;如下列表。</TD>
            <TD><b>列印月份</b></TD>
            <TD align=left colspan=2>
            </TD>
        </TR>
    </TABLE>


    <table cellPadding='0' border=1 cellSpacing='0' width='100%' align=center style='border:1px solid black;border-collapse:collapse;font-size:12pt;'>
        <tr align=center  style='font-size:11pt;border:1px solid black;'>
            <td width=8%>差假<br>單號</td>
            <td width=12%>日期</td>
            <td width=8%>日/時數</td>
            <td width=12%>地點</td>
            <td width=6%>職務<br>代理人</td>
            <td width=6%>單位<br>主管</td>
            <td width=6%>教學<br>組長</td>
            <td width=6%>校長</td>
            <td width=6%>人事<br>主任</td>
            <td width=6%>差旅費<br>申請數</td>
            <td>出差事由</td>
        </tr>
        <tr align=center  style='font-size:10pt;border:1px solid black;'>
            <td>1</td>
            <td>2019-04-24 ~ 2019-04-24
            </td>
            <td>1 日 </td>
            <td align=left>線西</td>
            <td>王麒富</td>
            <td>王麒富</td>
            <td></td>
            <td></td>
            <td></td>
            <td>3 </td>
            <td align=left>測試公差假</td>
        </tr>
    </table>

    <table border='1' cellPadding='0' cellSpacing='0'  width=100%>
        <tr align='center'>
            <td width='8%' rowspan='2' >請領<br>單號</td>
            <td width='9%' rowspan='2' >日   期</td>
            <td  width='10%' rowspan='2'>起迄地點</td>
            <td  width='18%' rowspan='2'>工作記要</td>

            <td  width='20%' colspan='4'>交通費</td>
            <td  width='5%' rowspan='2'>住宿費</td>
            <td  width='5%' rowspan='2'>旅行業代收轉付</td>
            <td  width='5%' rowspan='2'>單據<br>號數</td>
            <td  width='5%' rowspan='2'>什費</td>
            <td  width='5%' rowspan='2'>合計</td>

            <td  width='10%' rowspan='2'>主任</td>
        </tr>
        <tr align='center'>
            <td  width='5%'>飛機</td>
            <td  width='5%'>汔車及捷運</td>
            <td  width='5%'>火車</td>
            <td  width='5%'>高鐵</td>
        </tr>
        <tr align='center'>
            <td>1-1</td>
            <td>2019-04-24</td>
            <td></td>
            <td align='left'></td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>120&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>120&nbsp;</td>
            <td></td>
        </tr>
        <tr align='center'>
            <td>1-2</td>
            <td>2019-04-24</td>
            <td></td>
            <td align='left'></td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>500&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>500&nbsp;</td>
            <td></td>
        </tr>
        <tr align='center'>
            <td>1-3</td>
            <td>2019-04-24</td>
            <td>123</td>
            <td align='left'>456</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td>0&nbsp;</td>
            <td></td>
        </tr>

        <tr>
            <td colspan=4>&nbsp;口有口無 提供住宿(請勾選)</td>
            <td align='right' colspan=7>總計</td>
            <td align='right' colspan=2><b> 620 </b>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>

    <table border='1' cellPadding='0' cellSpacing='0'　 width=100%>
        <tr><td colspan=5>請敘明交通工具種類：口客運　口捷運　口火車(復興)　口火車(莒光)　口火車(自強)</td></tr>
        <tr Height=50><td colspan=5>上列出差旅費合計： <ins><b> 新台幣陸佰貳拾元整 </b></ins> ，
                業經如數收訖。<div style="text-align: right;font-size:10pt;"><b>具領人</b>　　　　　　　　　　　　　　　　　　　(簽名或蓋章)</div></td></tr>
        <tr align='center'>
            <td width=20%>出差人</td>
            <td width=20%>單位主管</td>
            <td width=20%>人事單位</td>
            <td width=20%>會計單位</td>
            <td width=20%>機關首長</td></tr>
        <tr Height=60><td>&nbsp;<br><br></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
    </table>
@endsection
