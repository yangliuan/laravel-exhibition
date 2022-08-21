//测试用例23567条数据，内存4096M,使用colletion 批量方式导入
//chunkSize=1 nginx504;废除
//chunkSize=100
//first=>postman:6m20.82s,telescope:380723ms|second=>postman:6m21.78s,telescope:381700ms|3rd=>postman:6m28.50s,telescope:388458ms
//chunksize=800
//first=>postman:56.54s,telescope:56495ms|second=>postman:55.57s,,telescope:55000ms|3rd=>postman:58.51s,telescope:58451ms
//chunksize=1000
//first=>postman:54.44s,telescope:54383ms|second=>postman:54.45s,telescope:54394ms|3rd=>postman:48.94s,telescope:48895ms
//chunksize=1250
//first=>postman:47.17s,telescope:47126ms|second=>postman:48.50s,telescope:48460ms|3rd=>postman:53.93s,telescope:53884ms
//chunksize=1500
//first=>postman:46.10s,telescope:46072ms|second=>postman:47.26s,telescope:47213ms|3rd=>postman:47.17s,telescope:47126ms
//chunksize=1700
//first=>postman:55.90s,telescope:55828ms|second=>postman:54.32s,telescope:54252ms|3rd=>postman:55.21s,telescope:55165ms
//chunkSize=2000
//first=>postman:1m6.78s,telescope:66736ms|second=>postman:1m13.98s,telescope:73934ms|3rd=>postman:1m8.40s,telescope:68347ms
