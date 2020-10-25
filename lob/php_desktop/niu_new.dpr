program niu_new;
{$I cef.inc}

uses
  Forms,
  Windows,
  SysUtils,
  uCEFApplication,
  uCEFConstants,
  ufrmMain in 'ufrmMain.pas' { frmMain },
  unConfig in 'unConfig.pas',
  unRunOne in 'unRunOne.pas',
  ufrmSplash in 'ufrmSplash.pas' { frmSplash },
  ufrmModal in 'ufrmModal.pas' { frmModal },
  uframeChrome in 'uframeChrome.pas' { frameChrome: TFrame},
  unV8Extension in 'unV8Extension.pas',
  unMoudle in 'unMoudle.pas' { dbMoudle: TDataModule},
  unChromeMessage in 'unChromeMessage.pas',
  unCmdCli in 'unCmdCli.pas',
  unCEF in 'unCEF.pas';

{$R *.res}
// CEF3 needs to set the LARGEADDRESSAWARE flag which allows 32-bit processes to use up to 3GB of RAM.
{$SETPEFLAGS IMAGE_FILE_LARGE_ADDRESS_AWARE}

begin
{$IF CompilerVersion> 18}
  ReportMemoryLeaksOnShutdown := True;
{$IFEND}
  CreateGlobalCEFApp; // ������������ڵ�Ԫ��������ڴ�й©
  try
    if GlobalCEFApp.StartMainProcess then
    begin
      Application.Initialize;
      if unRunOne.AppHasRun(Application.Handle,
        '{89304366-F0B6-432A-870B-3FAB2B7C5514}') then
      begin
        Exit;
      end;
{$IFDEF DELPHI11_UP}
      Application.MainFormOnTaskBar := False; // �����ڼ���ᵼ��show���ڣ�͸����Ӱ�Ӷ�
{$ENDIF}
      Application.Title := FCaption;
      Application.CreateForm(TdbMoudle, dbMoudle);
      Application.CreateForm(TfrmMain, frmMain);
      Application.Run;
    end;
  finally // ����finally���ͷţ���ֹunRunOne.AppHasRun�˳����ͷ��ӽ���
    DestroyGlobalCEFApp;
  end;

end.
