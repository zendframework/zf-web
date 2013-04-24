CREATE TABLE [dbo].[queue] (
    [queue_id] [int] IDENTITY (1, 1) NOT NULL ,
    [queue_name] [varchar] (100) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [timeout] [smallint] NOT NULL 
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[queue] WITH NOCHECK ADD 
    CONSTRAINT [pk_queue_id] PRIMARY KEY  CLUSTERED 
    (
        [queue_id]
    )  ON [PRIMARY] 
GO

CREATE TABLE [dbo].[message] (
    [message_id] [bigint] IDENTITY (1, 1) NOT NULL ,
    [queue_id] [int] NOT NULL ,
    [handle] [varchar] (32) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
    [body] [varchar] (8000) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [md5] [char] (32) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL ,
    [timeout] [decimal](14, 4) NULL ,
    [created] [int] NOT NULL 
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[message] WITH NOCHECK ADD 
    CONSTRAINT [pk_message_id] PRIMARY KEY  CLUSTERED 
    (
        [message_id]
    )  ON [PRIMARY] 
GO

ALTER TABLE [dbo].[message] ADD 
    CONSTRAINT [fk_queueid_queue] FOREIGN KEY 
    (
        [queue_id]
    ) REFERENCES [dbo].[queue] (
        [queue_id]
    ) ON DELETE CASCADE  ON UPDATE CASCADE 
GO