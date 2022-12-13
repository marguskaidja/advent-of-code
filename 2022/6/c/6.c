#include <errno.h>
#include <string.h>
#include <stdio.h>
#include <stdlib.h>

void find_chunk_of_unique_characters(FILE * fp, unsigned long chunk_length)
{
    char * chunk = malloc(chunk_length);

    if (!chunk) {
        fprintf(stderr, "malloc(%lu): %s (%u)\n", chunk_length, strerror(errno), errno);
        exit(-1);
    }

    unsigned long duplicates_map[256];
    unsigned long num_duplicate_character_codes = 0;
    unsigned long pos_in_chunk = 0;
    unsigned long i;

    memset(duplicates_map, 0, sizeof(duplicates_map));

    // First read up until we have first full set of characters in the `chunk` buffer
    for (i = 0; i < chunk_length && !feof(fp); i++) {
        char ch = fgetc(fp);

        if (++duplicates_map[ch] == 2) {
            num_duplicate_character_codes++;
        }

        chunk[pos_in_chunk++] = ch;
    }

    if (i == chunk_length) {
        // Next read until the end of file.
        //
        // Each time the position in `chunk` exceeds it's size, we wrap it around,
        // thus starting again from position 0. Each time new character is added to `chunk`, we extract the old one and
        // check from `duplicates_map` if by removing it we can decrease `num_duplicates` variable.
        do {
            if (num_duplicate_character_codes == 0) {
                printf("Found chunk with unique characters ending at position %lu!\n", i);
                break;
            }

            if (feof(fp)) {
                break;
            }

            i++;

            pos_in_chunk = pos_in_chunk % chunk_length;

            // "Remove" the oldest character from `chunk`
            char old_ch = chunk[pos_in_chunk];

            if (--duplicates_map[old_ch] == 1) {
                num_duplicate_character_codes--;
            }

            // ... and add new character instead
            char new_ch = fgetc(fp);

            if (++duplicates_map[new_ch] == 2) {
                num_duplicate_character_codes++;
            }
    
            chunk[pos_in_chunk++] = new_ch;
        } while(1);

        if (num_duplicate_character_codes) {
            printf("There is no chunk with length of %lu consisting of unique characters!\n", chunk_length);
        }
    } else {
        printf("Data-stream is shorter than %lu characters!\n", chunk_length);
    }


    free(chunk);
}

int main(int argc, char * argv[])
{
    if (argc != 3) {
        fprintf(stderr, "This program finds first chunk with length <chunk-length> consisting of unique characters from <input-file>\n\n");
        fprintf(stderr, "Syntax: %s <input-file> <chunk-length>\n", argv[0]);
        exit(-1);
    }

    // Parse <input-file> parameter
    FILE * fp = fopen(argv[1], "r");

    if (!fp) {
        fprintf(stderr, "fopen(%s): %s (%i)\n", argv[1], strerror(errno), errno);
        exit(-1);
    }


    // Parse <chunk-length> parameter
    errno = 0;
    char *endptr;
    unsigned long chunk_length = strtoul(argv[2], &endptr, 10);

    if (argv[2][0] == '\0' || *endptr != '\0' || errno) {
        if (errno) {
            fprintf(stderr, "strtol(%s): %s (%i)\n", argv[2], strerror(errno), errno);
        } else {
            fprintf(stderr, "strtol(%s): invalid unsigned long\n", argv[2]);
        }

        exit(-1);
    } else if (chunk_length < 2 || chunk_length > 256) {
        fprintf(stderr, "<chunk-length> must be between 2..256 (got %lu)\n", chunk_length);
        exit(-1);
    }

    find_chunk_of_unique_characters(fp, chunk_length);
}
